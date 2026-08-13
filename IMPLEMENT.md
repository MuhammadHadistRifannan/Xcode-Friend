Iya. Dan setelah saya cek ulang **file `jcow_backup(3).sql` yang sebenarnya**, saya akan ubah rekomendasi sebelumnya sedikit: **jangan sekadar menambahkan Foreign Key ke database JCow lama.** Itu berisiko karena schema legacy-nya memang dirancang tanpa FK, banyak data disimpan sebagai counter/string/serialized value, dan beberapa tabel memakai relationship yang hanya dipahami oleh application code.

Dump yang kamu kirim adalah MySQL 5.7.44, dan pada level schema terlihat tabel-tabelnya menggunakan **MyISAM** serta tidak mendefinisikan FK. Misalnya `jcow_accounts`, `jcow_streams`, `jcow_comments`, `jcow_friends`, `jcow_groups`, dan tabel forum semuanya memakai MyISAM.  

Jadi modernisasi database sebaiknya menjadi **project tersendiri di dalam migrasi JCow → Laravel 13**.

---

# 1. Target Modernisasi Database

Target akhirnya bukan:

```text
JCow MyISAM
     ↓
Laravel
     ↓
MyISAM + FK
```

Tetapi:

```text
                 LEGACY JCOW
                     │
                     ▼
             Reverse Engineering
                     │
                     ▼
              Data Validation
                     │
                     ▼
              Target Data Model
                     │
                     ▼
            Laravel 13 Migrations
                     │
                     ▼
              MySQL InnoDB
                     │
          ┌──────────┼──────────┐
          ▼          ▼          ▼
       PK/FK      Indexes     Constraints
          │          │          │
          └──────────┼──────────┘
                     ▼
             Validated Database
```

**Prinsipnya: jangan memodernisasi legacy DB in-place.**

Buat **database baru** untuk Laravel.

---

# 2. Masalah Utama Database JCow Saat Ini

Dari SQL yang kamu kirim, ada beberapa masalah struktural.

## A. MyISAM

Contoh:

```sql
CREATE TABLE `jcow_accounts` (...)
ENGINE=MyISAM;
```

dan pola yang sama muncul di tabel-tabel lainnya. 

Target:

```sql
ENGINE=InnoDB
```

Kenapa?

Karena aplikasi Laravel kamu akan membutuhkan transaksi untuk operasi seperti:

```text
Create Friend Request
        ↓
Create Notification
        ↓
Update Relationship
```

atau:

```text
Create Group
        ↓
Create Membership
        ↓
Create Activity
```

Kalau salah satu gagal, idealnya semuanya rollback.

---

# 3. Jangan Tambahkan FK ke Legacy Database Langsung

Ini penting.

Misalnya kamu ingin melakukan:

```sql
ALTER TABLE jcow_comments
ADD CONSTRAINT fk_comments_user
FOREIGN KEY (uid)
REFERENCES jcow_accounts(id);
```

**Jangan langsung.**

Karena:

```text
jcow_comments.uid
```

secara logical memang menunjuk user.

Tapi database lama tidak menjamin seluruh data valid.

Contoh lainnya:

```text
jcow_friends.uid
jcow_friends.fid
```

keduanya kemungkinan menunjuk:

```text
jcow_accounts.id
```

Namun `jcow_friends` sendiri tidak punya PK, hanya index. 

Jadi kalau kita langsung pasang FK, migration bisa gagal karena:

* orphan records;
* duplicate relationships;
* invalid IDs;
* data legacy;
* relationship yang tidak konsisten.

---

# 4. Arsitektur Database yang Saya Sarankan

Target:

```text
MySQL
└── InnoDB
    │
    ├── users
    ├── profiles
    ├── roles
    ├── role_user
    │
    ├── posts
    ├── comments
    ├── likes
    │
    ├── friendships
    ├── friend_requests
    ├── follows
    │
    ├── groups
    ├── group_members
    ├── group_posts
    ├── group_topics
    │
    ├── forums
    ├── forum_threads
    ├── forum_posts
    │
    ├── stories
    ├── story_photos
    ├── tags
    ├── story_tag
    │
    ├── messages
    ├── notifications
    │
    └── reports
```

---

# 5. Tahap 1 — Freeze Legacy Database

Sebelum migration:

```text
JCow Production
       │
       ▼
   BACKUP FULL
       │
       ├── jcow_backup.sql
       └── checksum
```

Jangan melakukan transformasi langsung terhadap database production.

Buat:

```text
jcow_legacy
```

sebagai database read-only untuk proses migrasi.

Sedangkan:

```text
jcow_laravel
```

adalah database baru.

---

# 6. Tahap 2 — Inventory Semua Tabel

Database kamu memiliki banyak domain.

Contoh:

### User

```text
jcow_accounts
jcow_profiles
jcow_roles
jcow_banned
jcow_blacks
```

### Social

```text
jcow_friends
jcow_friend_reqs
jcow_followers
jcow_liked
jcow_favorites
jcow_votes
```

### Content

```text
jcow_stories
jcow_story_categories
jcow_story_cat_groups
jcow_story_photos
jcow_tags
jcow_tag_ids
```

### Groups

```text
jcow_groups
jcow_group_members
jcow_group_members_pending
jcow_group_posts
jcow_group_topics
jcow_group_polls
```

### Forum

```text
jcow_forums
jcow_forum_threads
jcow_forum_posts
jcow_forum_attachments
jcow_forum_polls
jcow_forum_subscribes
```

Dan seterusnya.

Ini harus dibuat menjadi **Migration Mapping Document**.

---

# 7. Tahap 3 — Buat Data Dictionary

Ini wajib.

Contoh:

## `jcow_accounts`

Legacy:

| Legacy     | Target              | Keterangan     |
| ---------- | ------------------- | -------------- |
| `id`       | `users.id`          | PK             |
| `email`    | `users.email`       | Email          |
| `username` | `users.username`    | Username       |
| `password` | `users.password`    | Hash legacy    |
| `created`  | `users.created_at`  | Unix timestamp |
| `disabled` | `users.status`      | Transform      |
| `avatar`   | `profiles.avatar`   | Dipindah       |
| `fullname` | `profiles.fullname` | Dipindah       |
| `location` | `profiles.location` | Dipindah       |
| `about_me` | `profiles.about_me` | Dipindah       |
| `gender`   | `profiles.gender`   | Dipindah       |

Dan ini bukan teori—`jcow_accounts` memang mencampur authentication, profile, activity, role, settings, dan statistik dalam satu tabel. 

---

# 8. Tahap 4 — Pecah `jcow_accounts`

Ini salah satu modernisasi terbesar.

Sekarang:

```text
jcow_accounts
```

memiliki:

```text
47 columns
```

Target:

```text
users
profiles
user_settings
```

### users

```text
id
username
email
password
status
email_verified_at
created_at
updated_at
```

### profiles

```text
id
user_id
fullname
avatar
signature
location
gender
about_me
birth_year
birth_month
birth_day
hide_age
country
state
locale
```

### user_settings

```text
id
user_id
settings
hide_me
```

---

# 9. Tahap 5 — Normalisasi Relationship

## Legacy

```text
jcow_friends

uid
fid
created
```

dan tidak memiliki PK/unique constraint. 

Data bahkan menyimpan:

```text
1 → 3
3 → 1

3 → 2
2 → 3
```



Ini menunjukkan friendship legacy disimpan dua arah.

### Target

Saya lebih menyarankan:

```text
friendships
------------
id
user_id
friend_id
status
created_at
updated_at
```

dengan constraint:

```text
UNIQUE(user_id, friend_id)
```

Atau lebih baik lagi, kalau business logic memungkinkan:

```text
friendships
------------
id
user_one_id
user_two_id
status
created_at
updated_at
```

dengan aturan:

```text
user_one_id < user_two_id
```

sehingga:

```text
1 ↔ 3
```

cukup disimpan sekali.

**Tapi ini harus diverifikasi terhadap source code JCow sebelum diputuskan final**, karena database saja tidak menjelaskan seluruh behavior.

---

# 10. Followers

Legacy:

```text
jcow_followers
----------------
uid
fid
```

dengan contoh:

```text
1 → 3
2 → 3
3 → 1
3 → 2
```



Target:

```text
follows
-------
id
follower_id
following_id
created_at
```

Constraint:

```sql
UNIQUE(follower_id, following_id)
```

FK:

```text
follower_id → users.id
following_id → users.id
```

---

# 11. Friend Request

Legacy:

```text
jcow_friend_reqs
```

memiliki:

```text
uid
fid
created
msg
```



Target:

```text
friend_requests
----------------
id
sender_id
receiver_id
message
status
created_at
updated_at
```

Status:

```text
pending
accepted
rejected
cancelled
```

Dengan:

```text
sender_id → users.id
receiver_id → users.id
```

---

# 12. Modernisasi Stream

Ini bagian yang menurut saya **paling tricky**.

Legacy:

```text
jcow_streams
```

memiliki:

```text
id
message
wall_id
uid
attachment
created
type
app
aid
hide
likes
```



Dan sample datanya menunjukkan `app` dan `aid` digunakan untuk menghubungkan activity ke module/content tertentu.

Contoh:

```text
app = blogs
aid = 80
```



Jadi jangan langsung bikin:

```text
posts
```

dan membuang `app`/`aid`.

Lebih baik analisis dulu apakah ini sebenarnya:

```text
Activity Feed
```

bukan sekadar Post.

Saya cenderung membuat:

```text
activities
-----------
id
user_id
type
subject_type
subject_id
content
created_at
```

Dengan polymorphic relationship:

```text
subject_type
subject_id
```

Misalnya:

```text
User created Story

activities
--------------------------------
user_id = 2
type = story_created
subject_type = Story
subject_id = 80
```

Ini jauh lebih modern daripada:

```text
app = "blogs"
aid = 80
```

---

# 13. Comments

Legacy:

```text
jcow_comments
```

memiliki:

```text
uid
target_id
stream_id
```



Masalahnya:

```text
target_id varchar(20)
```

sedangkan `stream_id` adalah integer.

Ini indikasi adanya **legacy polymorphic-ish relationship**.

Target:

```text
comments
--------
id
user_id
commentable_type
commentable_id
content
created_at
updated_at
```

Dengan:

```text
user_id → users.id
```

dan:

```text
commentable_type
commentable_id
```

untuk object yang dikomentari.

---

# 14. Likes

Legacy:

```text
jcow_liked
```

punya:

```text
uid
stream_id
```



Target:

```text
likes
-----
id
user_id
likeable_type
likeable_id
created_at
```

Constraint:

```text
UNIQUE(
    user_id,
    likeable_type,
    likeable_id
)
```

Jadi:

```text
User 1
   │
   └── Like
        │
        └── Post 10
```

tidak mungkin double-like.

---

# 15. Stories

Legacy `jcow_stories` cukup berat: ada content, author, category, counters, rating, stream, page, custom variables, bahkan serialized rating. 

Target:

```text
stories
-------
id
author_id
category_id
title
content
thumbnail
status
is_sticky
is_featured
views
created_at
updated_at
```

Kemudian:

```text
story_photos
story_tags
story_ratings
```

dipisahkan.

---

# 16. Jangan Simpan `tags` sebagai String

Legacy:

```text
jcow_stories.tags
```

adalah:

```text
varchar(255)
```

dan ada:

```text
jcow_tags
jcow_tag_ids
```

 

Jadi target:

```text
tags
----
id
name
```

dan pivot:

```text
story_tag
---------
story_id
tag_id
```

Dengan:

```text
PRIMARY KEY(story_id, tag_id)
```

---

# 17. Group Membership

Legacy memisahkan:

```text
jcow_group_members
jcow_group_members_pending
```



Target modern:

```text
group_members
-------------
id
group_id
user_id
status
nickname
about_me
hide_profile
joined_at
created_at
updated_at
```

Status:

```text
pending
active
rejected
banned
left
```

Sehingga tidak perlu dua tabel untuk state membership.

---

# 18. Groups

Legacy `jcow_groups` juga menyimpan counter:

```text
members
posts
topics
```

serta:

```text
creatorid
creator
```



Target:

```text
groups
------
id
creator_id
category_id
uri
name
slogan
description
logo
visibility
membership_policy
created_at
updated_at
```

Jangan menyimpan:

```text
creator
```

karena bisa didapat:

```sql
users.name
```

Dan jangan menjadikan:

```text
members
posts
topics
```

sebagai source of truth kecuali memang diperlukan sebagai cached counter.

---

# 19. Forum

Legacy forum sudah memiliki logical relationship:

```text
forums
  ↓
forum_threads
  ↓
forum_posts
```

Tetapi semuanya tanpa FK. `forum_threads.fid` mengarah secara logical ke forum, sedangkan `forum_posts.tid` mengarah ke thread.  

Target:

```text
forums
  │
  └── forum_threads
         │
         └── forum_posts
```

FK:

```text
forum_threads.forum_id
    → forums.id

forum_posts.thread_id
    → forum_threads.id

forum_posts.user_id
    → users.id
```

---

# 20. Forum Attachment

Legacy:

```text
forum_attachments
pid
tid
```



Target:

```text
forum_attachments
-----------------
id
post_id
file_path
original_name
description
size
downloads
created_at
```

FK:

```text
post_id → forum_posts.id
```

`tid` tidak perlu disimpan jika thread bisa diperoleh dari post.

Itu namanya **menghilangkan redundancy**.

---

# 21. Profile

Legacy:

```text
jcow_profiles
```

bahkan menjadikan `id` sebagai primary key:

```text
id
style_ids
custom_css
background
videoid
favorites
views
```



Kalau `id` memang satu-to-one dengan account:

```text
users
  │
  │ 1 : 1
  ▼
profiles
```

Target:

```sql
profiles.user_id
```

dengan:

```sql
UNIQUE(user_id)
```

dan FK:

```text
profiles.user_id → users.id
```

---

# 22. Role

Legacy:

```text
jcow_roles
```

hanya:

```text
id
name
```

sedangkan account menyimpan:

```text
roles varchar(255)
```



Ini sebaiknya dinormalisasi menjadi:

```text
roles
-----
id
name

role_user
---------
user_id
role_id
```

Dengan:

```text
users 1 ─── N role_user N ─── 1 roles
```

Jangan lagi:

```text
users.roles = "1|2|3"
```

---

# 23. Notification

Dari sample database, `jcow_messages` jelas digunakan untuk notifikasi seperti:

> user commented on your stream

dan memiliki:

```text
from_id
to_id
hasread
```



Target:

```text
notifications
-------------
id
user_id
actor_id
type
data
read_at
created_at
```

Contoh:

```text
actor_id = 1
user_id = 2
type = comment.created
data = {
    "comment_id": 7,
    "post_id": 3
}
```

Ini jauh lebih bersih daripada menyimpan HTML:

```html
<a href="...">admin</a> commented ...
```

di database.

Dan memang sample legacy menyimpan HTML lengkap di `message`. 

---

# 24. HTML di Database → Data, Bukan Presentation

Ini salah satu modernisasi penting.

Legacy:

```text
message =
<a href="...">admin</a> commented...
```

Target:

```text
notification
-------------
actor_id
user_id
type
data
```

View Laravel yang menentukan:

```text
Admin commented on your post
```

Jadi:

```text
Database
   ↓
Pure Data
   ↓
Service
   ↓
Blade/View
   ↓
HTML
```

bukan:

```text
Database
   ↓
HTML
```

---

# 25. Serialized PHP Data

Ada contoh di `jcow_stories.rating`:

```text
a:1:{s:6:"rating";a:2:{...}}
```



Ini **jangan dibawa ke Laravel sebagai serialized PHP**.

Target:

```text
story_ratings
-------------
id
story_id
user_id
score
created_at
updated_at
```

atau jika rating hanya aggregate:

```text
rating
rating_count
```

Tapi ini perlu disesuaikan dengan business logic source code.

---

# 26. `var1`, `var2`, `var3` dan Sejenisnya

Legacy memiliki banyak:

```text
var1
var2
var3
...
```

Contohnya `jcow_accounts` dan `jcow_stories`.  

**Jangan otomatis membuat:**

```text
var1
var2
var3
```

di database baru.

Kita harus mencari source code:

```text
var1 digunakan untuk apa?
var2 digunakan untuk apa?
```

Kemudian ubah menjadi field domain yang meaningful.

Misalnya:

```text
var1 → cover_position
```

atau:

```text
var2 → video_provider
```

Kalau ternyata sudah tidak digunakan:

```text
DROP
```

Ini baru benar-benar modernisasi.

---

# 27. Counter Harus Dipisahkan dari Source of Truth

Legacy:

```text
accounts.followers
groups.members
groups.posts
stories.comments
streams.likes
```

Contohnya `jcow_accounts.followers` memang ada sebagai integer. 

Jangan menganggap:

```text
followers = 100
```

sebagai data utama.

Data utama:

```text
follows
```

Kemudian:

```sql
COUNT(follows.id)
```

menjadi source of truth.

Kalau performance nanti membutuhkan counter cache:

```text
followers_count
```

boleh ditambahkan.

Tapi statusnya:

> **derived data / cache**

bukan primary data.

---

# 28. Timestamp

Legacy menggunakan Unix timestamp:

```text
created int(11)
updated int(11)
lastlogin int(11)
```

Misalnya `jcow_accounts.created` dan `lastlogin` memang berupa integer. 

Target:

```text
created_at DATETIME
updated_at DATETIME
```

Migration:

```text
1786542956
      ↓
2026-08-13 01:xx:xx
```

Laravel kemudian bisa menggunakan:

```php
created_at
updated_at
```

secara native.

---

# 29. Primary Key

Banyak tabel legacy punya PK yang benar:

```text
accounts.id
comments.id
groups.id
stories.id
```

tetapi beberapa relationship tables tidak.

Contoh:

```text
jcow_followers
uid
fid
```

tidak memiliki primary key. 

Target:

```text
follows
id BIGINT UNSIGNED
```

atau composite:

```text
PRIMARY KEY(follower_id, following_id)
```

Saya cenderung menggunakan:

```text
id
+
UNIQUE(follower_id, following_id)
```

karena lebih nyaman dengan Eloquent.

---

# 30. Indexing Strategy

Setelah FK, bagian berikutnya adalah indexing.

Contoh:

```text
users
 ├── UNIQUE(email)
 └── UNIQUE(username)

posts
 ├── INDEX(user_id)
 └── INDEX(created_at)

comments
 ├── INDEX(user_id)
 └── INDEX(commentable_type, commentable_id)

likes
 └── UNIQUE(user_id, likeable_type, likeable_id)

follows
 └── UNIQUE(follower_id, following_id)
```

Untuk feed:

```text
INDEX(user_id, created_at)
```

atau berdasarkan query aktual kalian.

**Jangan bikin index di semua kolom.**

Index harus mengikuti query pattern.

---

# 31. Constraint yang Perlu Ditambahkan

Target database harus mulai menjaga business integrity.

### User

```text
UNIQUE username
UNIQUE email
```

### Follow

```text
UNIQUE follower_id + following_id
```

### Like

```text
UNIQUE user_id + likeable
```

### Group membership

```text
UNIQUE group_id + user_id
```

### Story tag

```text
UNIQUE story_id + tag_id
```

### Forum subscription

```text
UNIQUE user_id + thread_id
```

---

# 32. Foreign Key Strategy

Saya sarankan:

```text
ON DELETE CASCADE
```

hanya untuk relationship yang benar-benar dependent.

Contoh:

```text
users
  ↓
profiles
```

Jika user dihapus:

```text
profile
```

boleh ikut hilang.

Tetapi untuk:

```text
users
  ↓
posts
```

belum tentu harus cascade.

Bisa:

```text
user deleted
     ↓
posts.user_id = NULL
```

kalau kalian ingin mempertahankan post.

Jadi **jangan copy-paste `cascade()` ke semua migration**.

---

# 33. Contoh Target Laravel Migration

Misalnya:

```php
Schema::create('follows', function (Blueprint $table) {
    $table->id();

    $table->foreignId('follower_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('following_id')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->timestamps();

    $table->unique([
        'follower_id',
        'following_id',
    ]);
});
```

Ini baru database yang **ikut menjaga business rule**.

Bukan cuma PHP yang berharap semua developer ingat constraint-nya.

---

# 34. Urutan Migration yang Saya Rekomendasikan

Jangan random.

Gunakan dependency order:

```text
01 users
02 roles
03 role_user
04 profiles
05 user_settings

06 friend_requests
07 friendships
08 follows

09 groups
10 group_members
11 group_categories
12 group_topics
13 group_posts

14 forums
15 forum_threads
16 forum_posts
17 forum_attachments
18 forum_subscriptions

19 stories
20 story_categories
21 story_photos
22 tags
23 story_tags

24 posts/activities
25 comments
26 likes

27 messages
28 notifications

29 pages
30 page_users

31 reports
```

Tapi **urutan final harus mengikuti Target ERD final**.

---

# 35. Proses Migrasi Data

Saya sarankan pipeline seperti ini:

```text
             LEGACY DB
                 │
                 ▼
          Extract / Read
                 │
                 ▼
          Staging Tables
                 │
                 ▼
         Data Transformation
                 │
        ┌────────┼────────┐
        ▼        ▼        ▼
     Clean    Normalize  Convert
        │        │        │
        └────────┼────────┘
                 ▼
          Target Laravel DB
                 │
                 ▼
           FK Validation
                 │
                 ▼
           Business Tests
                 │
                 ▼
              READY
```

---

# 36. Buat Staging Database

Ini sangat saya rekomendasikan.

```text
mysql
├── jcow_legacy
├── jcow_staging
└── jcow_laravel
```

### `jcow_legacy`

Tidak disentuh.

### `jcow_staging`

Tempat:

```text
clean
transform
normalize
validate
```

### `jcow_laravel`

Database final.

---

# 37. Contoh Migration User

```text
jcow_accounts
      │
      ├───────────────┐
      ▼               ▼
    users          profiles
      │
      ▼
 role_user
```

Proses:

```text
for each legacy account:

    validate email
    validate username
    transform timestamp
    transform password
    create user
    create profile
    create role relationship
```

Dan **jangan melakukan semuanya dalam satu SQL INSERT raksasa**.

Untuk proyek Laravel, lebih baik dibuat migration/import command yang bisa:

```text
--dry-run
--limit
--offset
--resume
```

supaya kalau error di user ke-10.000, kamu tidak harus mengulang dari nol.

---

# 38. Data Validation Sebelum FK

Ini fase paling penting.

Misalnya kita mau:

```text
comments.uid → users.id
```

jalankan:

```sql
SELECT c.*
FROM jcow_comments c
LEFT JOIN users u
    ON u.id = c.uid
WHERE u.id IS NULL;
```

Kalau hasil:

```text
0 rows
```

berarti aman.

Kalau:

```text
17 rows
```

berarti ada 17 orphan comments.

---

# 39. Validation Matrix

Buat dokumen:

### `DATABASE-MIGRATION-CHECKLIST.md`

Contoh:

```text
USER
[ ] Semua accounts migrated
[ ] Username unique
[ ] Email unique
[ ] Password valid
[ ] Profile migrated
[ ] Role migrated

SOCIAL
[ ] Friend requests migrated
[ ] Friendships migrated
[ ] Follows migrated
[ ] Likes migrated
[ ] Comments migrated

GROUP
[ ] Groups migrated
[ ] Members migrated
[ ] Topics migrated
[ ] Posts migrated

FORUM
[ ] Forums migrated
[ ] Threads migrated
[ ] Posts migrated
[ ] Attachments migrated

CONTENT
[ ] Stories migrated
[ ] Photos migrated
[ ] Tags migrated
```

---

# 40. Row Count Validation

Setelah migration:

```text
Legacy                Laravel

accounts       =      users
stories        =      stories
friends        →      friendships
followers      →      follows
comments       =      comments
liked          →      likes
```

Tapi jangan selalu berharap:

```text
legacy count == target count
```

karena normalisasi bisa mengubah jumlah record.

Contoh:

```text
jcow_friends
```

punya:

```text
1 → 3
3 → 1
```

Target bisa menjadi:

```text
1 ↔ 3
```

satu record.

Jadi validation harus berdasarkan **business semantics**, bukan hanya jumlah row.

---

# 41. Business Validation

Contoh:

### Legacy

```text
User 1 friends with User 3
```

Target harus menjawab:

```sql
SELECT *
FROM friendships
WHERE ...
```

dan menghasilkan:

```text
User 1 ↔ User 3
```

Begitu juga:

```text
User 1 follows User 3
```

harus tetap:

```text
1 → 3
```

Jangan sampai migrasi friendship dan follow tertukar.

---

# 42. Migration Test

Buat automated test:

```text
Legacy Data
     ↓
Migration
     ↓
Target DB
     ↓
Assertions
```

Contoh:

```php
$this->assertDatabaseHas('users', [
    'username' => 'admin',
]);

$this->assertDatabaseHas('follows', [
    'follower_id' => 1,
    'following_id' => 3,
]);
```

---

# 43. Rollback Strategy

Jangan:

```text
migration gagal
↓
panik
```

😂

Harus:

```text
Legacy DB
     │
     ├── Backup
     │
     ▼
Migration
     │
     ├── FAIL → Drop Target DB
     │
     └── SUCCESS → Validation
```

Legacy tidak disentuh.

Jadi rollback cukup:

```text
DROP / recreate jcow_laravel
```

dan ulangi migration.

---

# 44. Database Modernization Roadmap

Untuk proyek 3 bulan kalian, saya akan masukkan ini ke PRD:

## Phase 1 — Reverse Engineering

**Week 1**

```text
[ ] Inventory 51 tables
[ ] Identify domains
[ ] Identify logical FK
[ ] Identify denormalized fields
[ ] Identify serialized data
[ ] Identify legacy counters
[ ] Identify unused tables
```

---

## Phase 2 — Target Design

**Week 1–2**

```text
[ ] Target ERD
[ ] Data Dictionary
[ ] Migration Mapping
[ ] Naming convention
[ ] PK strategy
[ ] FK strategy
[ ] Index strategy
[ ] Delete strategy
```

---

## Phase 3 — Database Foundation

**Week 2–3**

```text
[ ] Laravel migrations
[ ] InnoDB
[ ] PK
[ ] FK
[ ] Unique constraints
[ ] Indexes
[ ] Enum/status strategy
[ ] Timestamps
```

---

## Phase 4 — Data Migration Prototype

**Week 3–4**

```text
[ ] users
[ ] profiles
[ ] roles
[ ] friendships
[ ] follows
[ ] posts/activities
```

Kemudian:

```text
[ ] Validation
[ ] Re-run
[ ] Rollback
```

---

## Phase 5 — Feature Migration

**Week 4–8**

```text
Groups
Forums
Stories
Messaging
Notifications
Pages
```

---

## Phase 6 — Data Integrity

**Week 8–9**

```text
[ ] FK validation
[ ] orphan detection
[ ] duplicate detection
[ ] null validation
[ ] counter validation
[ ] relationship validation
```

---

## Phase 7 — Performance

**Week 9–10**

```text
[ ] EXPLAIN
[ ] Index optimization
[ ] N+1 detection
[ ] Pagination
[ ] Counter caching
[ ] Query optimization
```

---

## Phase 8 — Final Migration

**Week 11**

```text
Legacy Backup
      ↓
Final Migration
      ↓
Validation
      ↓
UAT
```

---

## Phase 9 — Cutover

**Week 12**

```text
JCow Legacy
     │
     ▼
FINAL BACKUP
     │
     ▼
FINAL MIGRATION
     │
     ▼
Laravel 13
     │
     ▼
Smoke Test
     │
     ▼
Production
```

---

# 45. Pembagian Jobdesk 6 Orang

Dengan tim kalian:

```text
Hadist  → BE / Tech Lead
Daffa   → BE
Akmal   → FE
Giska   → FE
Bima    → UI/UX
Ifan    → UI/UX
```

Saya akan memasukkan **database modernization terutama ke Backend**, tetapi bukan berarti hanya kamu dan Daffa yang kerja.

### Hadist — Tech Lead / BE

```text
Target ERD
Migration Architecture
FK Strategy
Data Migration Strategy
Laravel Architecture
Code Review
Critical Backend
```

### Daffa — BE

```text
Laravel migrations
Models
Repositories
Migration scripts
Data validation
Seeders
Database tests
```

### Akmal + Giska — FE

```text
Feature requirements
Empty states
Error states
Pagination requirements
Data display requirements
Integration
```

### Bima + Ifan — UI/UX

```text
Design System
Data states
Empty state
Error state
Loading state
Responsive UI
```

Jadi database modernization bukan pekerjaan "BE selesai sendiri lalu FE tinggal menerima."

---

# 46. Definition of Done Database

Saya akan tambahkan ke PRD kalian:

> **Database Migration dianggap DONE apabila:**

```text
[✓] Target ERD disetujui
[✓] Semua target table menggunakan InnoDB
[✓] Primary key tersedia
[✓] Logical relationship sudah dimodelkan
[✓] Foreign key sudah diterapkan
[✓] Unique constraint diterapkan
[✓] Index sesuai query pattern
[✓] Legacy timestamp sudah dikonversi
[✓] Serialized data sudah ditransformasi
[✓] Redundant data sudah dianalisis
[✓] Orphan records = 0
[✓] Duplicate relationship = 0
[✓] Row/business validation passed
[✓] Migration dapat dijalankan ulang
[✓] Rollback strategy tersedia
[✓] Automated database tests passed
```

---

# 47. Target Akhir

Jadi transformasi database kalian seharusnya seperti ini:

```text
                    JCOW LEGACY
                         │
                         │
              ┌──────────▼──────────┐
              │   51 Legacy Tables  │
              │       MyISAM        │
              │      No FK          │
              └──────────┬──────────┘
                         │
                   ANALYSIS
                         │
              ┌──────────▼──────────┐
              │  Domain Mapping     │
              │  Data Dictionary    │
              │  Relationship Map   │
              └──────────┬──────────┘
                         │
                    TRANSFORM
                         │
              ┌──────────▼──────────┐
              │ Target Laravel DB   │
              │      InnoDB         │
              │                     │
              │ PK + FK + Index     │
              │ Constraints         │
              │ Normalized Data     │
              └──────────┬──────────┘
                         │
                    VALIDATION
                         │
              ┌──────────▼──────────┐
              │ Business Integrity  │
              │ Data Integrity      │
              │ Automated Tests     │
              └──────────┬──────────┘
                         │
                         ▼
                  Laravel 13 App
```

### Yang paling penting:

**Jangan menganggap "menambahkan Foreign Key" = modernisasi database.**

Modernisasi database kalian terdiri dari **6 pekerjaan besar**:

> **Normalize → Rename → Transform → Relate → Constrain → Validate**

Dan khusus database JCow ini, saya sangat menyarankan **tidak melakukan in-place migration**. Buat **Target Database Laravel 13 dari nol**, lalu migrasikan data dari `jcow_legacy` ke database baru. Itu memang pekerjaan lebih banyak di awal, tapi jauh lebih aman dan hasil akhirnya benar-benar modern.

Satu hal lagi yang perlu kita lakukan sebelum saya mengunci **Target ERD final**: `jcow_streams`, `target_id` pada comments, `app/aid`, `var1-var7`, `votes`, dan beberapa field serialized **belum bisa dipastikan maknanya hanya dari SQL**. Untuk itu source code JCow (`xcode_friends.zip`) perlu kita reverse-engineer juga. Kalau langsung kita desain tanpa itu, kita berisiko "memodernisasi" data sambil diam-diam mengubah business logic.
