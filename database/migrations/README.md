# Laravel Migrations — xcode_friends (JCow Social Network Schema)

Migration ini dihasilkan dari dump database `xcode_friends.sql` (schema JCow / social-network
PHP script lama, MySQL 5.5, engine MyISAM). Total **53 tabel** berhasil di-convert menjadi
53 file migration Laravel (format anonymous class, kompatibel Laravel 9/10/11/12).

## Cara pakai
1. Copy semua file di folder `migrations/` ke folder `database/migrations/` project Laravel kamu.
2. Jalankan:
   ```
   php artisan migrate
   ```

## Catatan penting / hal yang perlu direview manual

- **Primary key**: kolom `id` int/bigint yang `AUTO_INCREMENT` + `PRIMARY KEY` di-convert
  jadi `$table->id()`. Beberapa tabel legacy (`jcow_roles`, `jcow_profiles`) punya `id`
  sebagai primary key TANPA auto_increment (biasanya foreign key ke `jcow_accounts.id`) —
  ini dipertahankan apa adanya sebagai `integer` + `->primary()`.
- **Tabel tanpa primary key** (contoh: `jcow_cache`, `jcow_followers`, `jcow_friends`,
  `jcow_gvars`, `jcow_texts`, `jcow_tmp`, dll) memang tidak punya PK di database aslinya —
  dibiarkan tanpa PK, sesuai dump asli. Kalau mau tambah PK auto-increment sendiri,
  tinggal tambahkan `$table->id();` di baris paling atas masing-masing file.
- **Index/KEY**: semua `KEY` dan `UNIQUE KEY` dari dump di-convert ke `->index()` /
  `->unique()`, dengan nama index diberi prefix nama tabel supaya tidak bentrok
  antar-migration (contoh: `jcow_accounts_username`).
- **Tipe data**:
  - `tinyint` → `tinyInteger` (bukan `boolean`, karena beberapa kolom seperti `level`,
    `disabled`, `gender` bisa berisi lebih dari 0/1).
  - `blob` → `binary`.
  - `tinytext` → `tinyText()`.
  - Kolom waktu (`created`, `updated`, `lastlogin`, dst.) di schema asli disimpan sebagai
    **unix timestamp `int`**, bukan kolom `datetime`/`timestamp` Laravel — jadi TIDAK dipakai
    `$table->timestamps()`, melainkan tetap `integer` sesuai aslinya.
- **Engine**: dump asli pakai `MyISAM` (tidak support foreign key constraint). Migration ini
  tidak menetapkan engine (default InnoDB Laravel) supaya kamu bisa nambah foreign key
  kalau perlu. Kalau butuh strict MyISAM, tambahkan `$table->engine = 'MyISAM';` di masing2
  file sebelum kolom-kolom.
- **Foreign keys**: dump asli TIDAK punya foreign key constraint sama sekali (semua relasi
  cuma berupa kolom id biasa, khas aplikasi lama). Migration ini juga tidak menambahkan FK
  otomatis, supaya proses import data lama (kalau ada) tidak gagal karena data yatim/orphan.
  Kamu bisa tambahkan FK manual setelah data bersih.
- Urutan file migration mengikuti urutan tabel di dump SQL (alfabetis apa adanya), karena
  memang tidak ada dependency foreign key yang mengharuskan urutan tertentu.

## Daftar 53 tabel
jcow_accounts, jcow_banned, jcow_blacks, jcow_cache, jcow_chatbar, jcow_chatrooms,
jcow_comments, jcow_favorites, jcow_followers, jcow_forum_attachments, jcow_forum_polls,
jcow_forum_posts, jcow_forum_subscribes, jcow_forum_threads, jcow_forums, jcow_friend_reqs,
jcow_friends, jcow_group_categories, jcow_group_members, jcow_group_members_pending,
jcow_group_polls, jcow_group_postcats, jcow_group_posts, jcow_group_topics, jcow_groups,
jcow_gvars, jcow_invites, jcow_langs, jcow_liked, jcow_menu, jcow_messages,
jcow_messages_sent, jcow_modules, jcow_page_users, jcow_pages, jcow_profile_comments,
jcow_profiles, jcow_reports, jcow_roles, jcow_stories, jcow_story_cat_groups,
jcow_story_categories, jcow_story_photos, jcow_streams, jcow_subscr, jcow_tag_ids,
jcow_tags, jcow_texts, jcow_tmp, jcow_user_crafts, jcow_var_cache, jcow_votes, ratings
