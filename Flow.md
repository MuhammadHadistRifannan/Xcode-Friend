# JCow Legacy --- Business Flow & Feature Specification

**Dokumen:** Legacy Business Flow Analysis\
**Project:** Modernisasi JCow Social Network → Laravel 13\
**Basis analisis:** source code JCow yang diunggah
(`xcode_friends(1).zip`) + struktur database legacy yang sebelumnya
dianalisis\
**Status:** Draft baseline untuk reverse engineering\
**Tanggal:** 13 Agustus 2026

------------------------------------------------------------------------

## 1. Tujuan Dokumen

Dokumen ini mendeskripsikan **alur bisnis aktual aplikasi JCow legacy**,
bukan desain Laravel baru.

Tujuannya adalah:

1.  Memetakan role dan hak akses legacy.
2.  Menjelaskan apa yang dapat dilakukan Guest, General Member, dan
    Administrator.
3.  Menjelaskan alur bisnis setiap fitur dari awal sampai akhir.
4.  Menghubungkan fitur dengan modul dan data legacy.
5.  Menjadi baseline untuk proses migrasi ke Laravel 13.
6.  Mencegah tim menghilangkan business rule ketika melakukan
    modernization.
7.  Menjadi acuan untuk menyusun Service, Controller, Repository, View,
    Policy, Middleware, dan migration pada sistem baru.

> **Catatan penting:** JCow memiliki sistem permission yang lebih
> fleksibel daripada hanya tiga role. Secara default database
> mendefinisikan `Guest`, `General member`, dan `Administrator`, tetapi
> administrator dapat membuat role tambahan dan memberikan permission
> tertentu. Karena project modernization saat ini menetapkan tiga role
> utama, dokumen ini memetakan ketiganya sebagai baseline, sambil tetap
> mencatat behavior permission legacy yang lebih granular.

------------------------------------------------------------------------

# 2. Role & Access Model Legacy

## 2.1 Role resmi bawaan

Database legacy mendefinisikan:

    ID Role             Makna
  ---- ---------------- ----------------------------------
     1 Guest            Pengunjung yang belum login
     2 General member   Member yang sudah login
     3 Administrator    Administrator dengan akses penuh

Source code juga memperlakukan:

-   role `1` sebagai Guest;
-   role `2` sebagai authenticated/general member;
-   role `3` sebagai Administrator.

Pada setiap request, user yang login mendapatkan role `2`, sedangkan
visitor mendapatkan role `1`. Administrator dengan role `3` mendapatkan
bypass pada fungsi `allow_access()`.

------------------------------------------------------------------------

# 3. Konsep Access Control Legacy

JCow tidak hanya menggunakan role.

Ada beberapa layer:

``` text
Role
  ↓
Permission
  ↓
Authentication
  ↓
Ownership
  ↓
Privacy
  ↓
Feature-specific rule
```

Contoh:

``` text
General Member
    ↓
boleh membuat post
    ↓
tetapi hanya pada Page/Group tempat dia memiliki akses
```

Contoh lain:

``` text
General Member
    ↓
boleh edit post
    ↓
hanya post miliknya
```

Sedangkan:

``` text
Administrator
    ↓
dapat bypass sebagian besar ownership check
```

------------------------------------------------------------------------

# 4. Default Permission Configuration

Konfigurasi default legacy yang ditemukan:

  Permission             Default Role
  ---------------------- ---------------------------------
  `permission_etheme`    General member
  `permission_atheme`    General member + custom role 11
  `permission_upload`    General member
  `permission_comment`   General member
  `permission_add`       General member
  `permission_browse`    Guest + General member
  `permission_feed`      Guest + General member

Dengan demikian, secara default:

-   Guest dapat mengakses browse member.
-   Guest dapat mengakses feed jika permission feed tetap seperti
    default.
-   General Member dapat membuat content.
-   General Member dapat upload.
-   General Member dapat comment.
-   Administrator dapat melewati `allow_access()`.

------------------------------------------------------------------------

# 5. ROLE 1 --- GUEST

## 5.1 Definisi

Guest adalah visitor yang belum memiliki authenticated session.

Guest tidak mempunyai `client.id`.

Guest tidak dapat menggunakan fitur yang secara eksplisit memanggil
`need_login()` atau membutuhkan role member.

Namun Guest tetap dapat mengakses beberapa area publik sesuai
konfigurasi.

------------------------------------------------------------------------

# 6. Guest --- Alur Masuk ke Sistem

``` text
Visitor membuka website
        ↓
Tidak terdapat authenticated session
        ↓
System menganggap visitor sebagai Guest
        ↓
Guest mendapatkan role 1
        ↓
System mengecek apakah halaman public
        ↓
Public → tampilkan halaman
Private/member-only → redirect/login/access denied
```

------------------------------------------------------------------------

# 7. Guest --- Home / Landing Page

Guest dapat memasuki halaman utama apabila network tidak dikonfigurasi
sebagai private network.

Behavior dikontrol oleh konfigurasi:

``` text
private_network
```

Jika:

``` text
private_network = 0
```

maka:

``` text
Guest
  ↓
Home
```

Jika:

``` text
private_network = 1
```

maka:

``` text
Guest
  ↓
harus login
```

------------------------------------------------------------------------

# 8. Guest --- Browse Member

Menu legacy:

``` text
/browse
```

Default permission:

``` text
1|2
```

Artinya:

``` text
Guest       → boleh
General     → boleh
Administrator → boleh
```

### Fungsi

Guest dapat mencari member berdasarkan:

-   gender;
-   rentang umur;
-   lokasi;
-   custom profile field;
-   last login;
-   registration;
-   jumlah follower.

### Flow

``` text
Guest
  ↓
Browse Members
  ↓
Set filter
  ├── Gender
  ├── Age
  ├── Location
  └── Custom fields
  ↓
Set sorting
  ├── Last Login
  ├── Registration
  └── Top Followed
  ↓
System query accounts
  ↓
Display member list
```

Guest dapat membuka profile member yang memang public.

------------------------------------------------------------------------

# 9. Guest --- Melihat Profile Member

Guest dapat melihat profile apabila privacy member mengizinkan.

Privacy profile legacy memiliki tiga mode:

``` text
0 = Everyone
1 = Friends of friends
2 = Friends only
```

### Flow

``` text
Guest membuka /u/{username}
        ↓
System membaca profile owner
        ↓
Check privacy
        ↓
Public
  → tampilkan
```

Untuk profile dengan privacy yang membutuhkan authenticated
relationship:

``` text
Guest
  ↓
tidak memiliki relationship
  ↓
access denied
```

------------------------------------------------------------------------

# 10. Guest --- Content Public

Guest dapat melihat content yang public sesuai konfigurasi aplikasi.

Content legacy mencakup:

-   Blog;
-   Photo;
-   Video;
-   Event;
-   Page;
-   Group yang public;
-   User profile;
-   beberapa stream/feed.

Namun kemampuan Guest bersifat read-only.

------------------------------------------------------------------------

# 11. Guest --- Yang Tidak Bisa Dilakukan

Guest tidak dapat melakukan operasi yang membutuhkan authenticated
member.

Contoh:

-   membuat post;
-   membuat blog;
-   membuat album;
-   upload photo;
-   upload music;
-   membuat video;
-   comment;
-   like;
-   follow;
-   add friend;
-   menerima/approve friend request;
-   private message;
-   membuat group;
-   membuat Page;
-   join group;
-   leave group;
-   edit profile;
-   mengakses account;
-   mengakses inbox;
-   mengakses notification pribadi;
-   mengakses dashboard pribadi;
-   melakukan administrative action.

Jika Guest mencoba fitur tertentu, legacy biasanya:

``` text
redirect → member/login
```

atau:

``` text
access denied
```

tergantung implementasi modul.

------------------------------------------------------------------------

# 12. Guest --- Search

Module:

``` text
search
```

tersedia pada aplikasi.

Search berfungsi sebagai discovery mechanism untuk content/member sesuai
module yang aktif.

Behavior detail search legacy harus dianggap sebagai feature yang perlu
direplikasi berdasarkan implementasi source module, bukan asumsi search
modern.

------------------------------------------------------------------------

# 13. Guest --- Authentication

Guest dapat:

-   membuka login;
-   membuka signup;
-   menjalankan forgot/change password flow yang tersedia;
-   menjalankan account verification sesuai konfigurasi.

Flow login:

``` text
Guest
  ↓
Login
  ↓
Username/Email + Password
  ↓
Validate credentials
  ↓
Valid?
 ├── No → error
 └── Yes
       ↓
Create session
       ↓
Assign authenticated role
       ↓
Dashboard / requested page
```

------------------------------------------------------------------------

# 14. Guest --- Registration

Flow signup:

``` text
Guest
  ↓
Signup
  ↓
Isi:
  ├── username
  ├── email
  ├── password
  ├── fullname
  ├── gender
  ├── birth
  ├── location
  ├── about me
  └── custom fields
  ↓
Agree Rules & Conditions
  ↓
Captcha
  ↓
Validate
  ↓
Create account
```

Status account setelah signup bergantung pada konfigurasi:

``` text
acc_verify
```

Kemungkinan:

``` text
No verification
        ↓
Account active

Email verification
        ↓
Account disabled/pending
        ↓
Send verification code
        ↓
Member verifies
        ↓
Account active

Administrator approval
        ↓
Account pending
        ↓
Administrator approves
        ↓
Account active
```

------------------------------------------------------------------------

# 15. ROLE 2 --- GENERAL MEMBER

## 15.1 Definisi

General Member adalah user authenticated yang mempunyai role default
`2`.

Member adalah aktor utama social network.

Fitur utamanya:

``` text
Account
Profile
Social Relationship
Feed
Posts
Blogs
Photos
Videos
Music
Events
Groups
Pages
Messaging
Notifications
Reports
```

------------------------------------------------------------------------

# 16. General Member --- Login Flow

``` text
Member
  ↓
Login
  ↓
Validate credentials
  ↓
Create session
  ↓
Load account
  ↓
Load roles
  ↓
Add General Member role
  ↓
Update last login
  ↓
Load settings
  ↓
Load personal Page
  ↓
Check account status
  ↓
Dashboard
```

Jika account disabled/pending:

``` text
Account pending
   ↓
Verification required
   OR
Administrator approval required
```

------------------------------------------------------------------------

# 17. General Member --- Dashboard

Menu:

``` text
/dashboard
```

Dashboard merupakan personal area.

Konsepnya:

``` text
Member
  ↓
Dashboard
  ├── Personal information
  ├── Activity/content
  ├── Personal modules
  ├── Social activity
  └── Account navigation
```

Dashboard merupakan entry point setelah authenticated session.

------------------------------------------------------------------------

# 18. General Member --- Account Management

Menu:

``` text
/account
```

Submenu:

``` text
My information
Avatar
Notifications
Privacy
Password
```

------------------------------------------------------------------------

# 19. Edit Profile

Member dapat mengubah:

-   Full Name;
-   Gender;
-   Birth year;
-   Birth month;
-   Birth day;
-   Hide age;
-   Location;
-   About Me;
-   custom fields.

Flow:

``` text
Member
  ↓
My Account
  ↓
My Information
  ↓
Edit profile
  ↓
Validate required fields
  ↓
Save
  ↓
Update accounts
  ↓
Redirect profile
```

------------------------------------------------------------------------

# 20. Avatar Management

Member dapat mengupload avatar.

Flow:

``` text
Account
  ↓
Avatar
  ↓
Select image
  ↓
Upload
  ↓
Resize
  ↓
Generate image variants
  ↓
Save avatar path
  ↓
Update account
```

------------------------------------------------------------------------

# 21. Notification Email Preferences

Member dapat mengatur email notification.

Legacy mendukung notification email untuk:

-   Private message;
-   Friend request;
-   Friend request confirmed;
-   Wall post;
-   Stream comment;
-   Group reply.

Flow:

``` text
Account
  ↓
Notifications
  ↓
Toggle notification types
  ↓
Save
  ↓
Serialize settings
  ↓
Store in account settings
```

------------------------------------------------------------------------

# 22. Privacy Management

Member dapat mengatur privacy profile.

Mode:

### Mode 0 --- Everyone

``` text
Everyone
  ↓
View profile
  ↓
Comment wall
```

### Mode 1 --- Everyone can view, friends can comment

``` text
Everyone
  ↓
View profile

Friends
  ↓
Comment wall
```

### Mode 2 --- Friends only

``` text
Non-friend
  ↓
Cannot view profile

Friend
  ↓
Can view profile
```

------------------------------------------------------------------------

# 23. Password Management

Member dapat mengganti password melalui:

``` text
/account/cpassword
```

Flow:

``` text
Member
  ↓
Password
  ↓
Input new password
  ↓
Validation
  ↓
Update credential
```

Authentication module juga memiliki password recovery flow.

------------------------------------------------------------------------

# 24. Social Relationship --- Follow

Endpoint utama:

``` text
follow/add/{uid}
follow/remove/{uid}
```

Flow Follow:

``` text
Member A
  ↓
Open Member B profile
  ↓
Click Follow
  ↓
Validate B
  ↓
Check already following
  ↓
Create follower relationship
  ↓
Increment B follower counter
  ↓
Redirect My Following
```

Relationship bersifat satu arah:

``` text
A → B
```

A mengikuti B.

B tidak otomatis mengikuti A.

------------------------------------------------------------------------

# 25. Social Relationship --- Unfollow

``` text
Member A
  ↓
Following B
  ↓
Unfollow
  ↓
Find A → B relationship
  ↓
Delete relationship
  ↓
Decrease B follower counter
```

------------------------------------------------------------------------

# 26. My Followers

Member dapat melihat:

``` text
/me followers
```

atau:

``` text
follow/myfollowers
```

Flow:

``` text
Member
  ↓
My Followers
  ↓
Query followers
  ↓
Pagination
  ↓
Display avatar + username
```

------------------------------------------------------------------------

# 27. My Following

Flow:

``` text
Member
  ↓
I'm Following
  ↓
Query accounts followed by member
  ↓
Pagination
  ↓
Display users
  ↓
Option Unfollow
```

------------------------------------------------------------------------

# 28. Social Relationship --- Friend Request

Friendship berbeda dengan Follow.

Follow:

``` text
A → B
```

Friend:

``` text
A ↔ B
```

Flow request:

``` text
Member A
  ↓
Open Member B
  ↓
Add Friend
  ↓
Validate B
  ↓
Prevent self-add
  ↓
Check existing friendship
  ↓
Create friend request
  ↓
Store optional message
  ↓
Send notification/email
```

------------------------------------------------------------------------

# 29. Friend Request Approval

Member B membuka:

``` text
friends/requests
```

System menampilkan request:

``` text
A wants to be friends with B
```

B dapat:

``` text
Approve
Reject
```

### Approve

``` text
B approves A
  ↓
Create A → B friendship
  ↓
Create B → A friendship
  ↓
Delete pending request
  ↓
Publish friendship activity
  ↓
Send confirmation notification
```

### Reject

``` text
B rejects
  ↓
Delete request
  ↓
No friendship created
```

------------------------------------------------------------------------

# 30. Friends Listing

Member dapat melihat:

-   daftar teman sendiri;
-   daftar teman user lain yang dapat dilihat;
-   avatar;
-   username;
-   profile.

Profile user memiliki tab:

``` text
Wall
Liked
Friends
Following
```

------------------------------------------------------------------------

# 31. Block / Blacklist

Member dapat block user lain.

Flow:

``` text
Member A
  ↓
Member B profile
  ↓
Block
  ↓
Create blacklist relationship
```

Jika sudah block:

``` text
Block button
    ↓
Unblock
```

Blacklist memengaruhi beberapa interaction seperti private message.

------------------------------------------------------------------------

# 32. Private Messaging

Module:

``` text
message
```

Area:

``` text
Inbox
Outbox
Compose
```

------------------------------------------------------------------------

# 33. Send Private Message

Flow:

``` text
Member A
  ↓
Open Member B profile
  ↓
Message
  ↓
Compose
  ↓
Subject optional
  ↓
Message required
  ↓
Validate recipient
  ↓
Check blocking condition
  ↓
Create incoming message
  ↓
Create sent-message record
  ↓
Send mail notification
  ↓
Record posting limit
```

------------------------------------------------------------------------

# 34. Inbox

Member dapat:

-   melihat incoming messages;
-   melihat sender;
-   subject;
-   timestamp;
-   unread status;
-   membuka message;
-   delete message.

Unread message diberi visual distinction.

------------------------------------------------------------------------

# 35. Message Detail

Flow:

``` text
Inbox
  ↓
Open message
  ↓
Display sender
  ↓
Display subject
  ↓
Display content
  ↓
Mark as read
  ↓
Option:
  ├── Reply
  └── Delete
```

------------------------------------------------------------------------

# 36. Outbox

Member dapat:

-   melihat sent messages;
-   melihat recipient;
-   membuka sent message;
-   delete sent message.

------------------------------------------------------------------------

# 37. News Feed

Module:

``` text
feed
```

Menu:

``` text
News feed
```

Default permission:

``` text
Guest + General Member
```

Feed memiliki beberapa perspektif:

``` text
Index
Following
Friends
All Streams
```

------------------------------------------------------------------------

# 38. Feed --- Index

Flow:

``` text
Member
  ↓
News Feed
  ↓
Load activity
  ↓
Display streams
  ↓
Pagination
```

Activity dapat berasal dari berbagai module.

------------------------------------------------------------------------

# 39. Feed --- Following

``` text
Member
  ↓
Following Feed
  ↓
Get users followed by member
  ↓
Get their streams
  ↓
Order by activity
  ↓
Display
```

------------------------------------------------------------------------

# 40. Feed --- Friends

``` text
Member
  ↓
Friends Feed
  ↓
Get friends
  ↓
Get friend streams
  ↓
Display activity
```

------------------------------------------------------------------------

# 41. Feed --- All Streams

``` text
Member
  ↓
All Streams
  ↓
Get public/general activity
  ↓
Display stream
```

------------------------------------------------------------------------

# 42. General Content Model

JCow menggunakan konsep `story` sebagai base abstraction.

Modul yang menggunakannya antara lain:

``` text
Blogs
Photos
Videos
Music
Events
```

Konsep dasarnya:

``` text
Story
 ├── Author
 ├── Page owner
 ├── Category
 ├── Title
 ├── Content
 ├── Privacy
 ├── Comments
 ├── Stream
 ├── Tags
 └── Attachments
```

------------------------------------------------------------------------

# 43. General Member --- Create Content

General content creation secara umum:

``` text
Member
  ↓
New Content
  ↓
Check permission_add
  ↓
Check posting limit
  ↓
Select destination Page
  ↓
Check Page access
  ↓
Input content
  ↓
Save
  ↓
Create story
  ↓
Create stream activity
  ↓
Save tags/attachments
  ↓
Redirect detail
```

------------------------------------------------------------------------

# 44. Page Access Rule untuk Content

Ini business rule penting.

Jika posting ke personal Page:

``` text
page.type = u
```

maka:

``` text
only page owner
```

yang boleh posting.

Jika posting ke group/Page:

``` text
page.uid != client.id
```

maka system memeriksa:

``` text
page_users
```

Jika member tidak terdaftar:

``` text
access denied
```

Jadi:

``` text
Create content
     ↓
Destination
     ↓
Personal?
 ├── Yes → owner only
 └── No
      ↓
   Member?
    ├── Yes → allowed
    └── No → denied
```

------------------------------------------------------------------------

# 45. Blogs

Module:

``` text
blogs
```

Feature:

-   create blog;
-   edit blog;
-   delete blog;
-   comment;
-   tags;
-   rating/voting;
-   favorite option;
-   following feed;
-   friends feed;
-   personal blog listing;
-   public/community listing;
-   activity stream.

Blog menggunakan:

``` text
tags = enabled
allow_vote = enabled
```

------------------------------------------------------------------------

# 46. Blog Creation Flow

``` text
Member
  ↓
New Blog
  ↓
Blog Title
  ↓
Content
  ↓
Tags
  ↓
Privacy
  ↓
Destination Page
  ↓
Check page access
  ↓
Create story
  ↓
Initialize rating
  ↓
Create stream activity
  ↓
Save tags
  ↓
Open blog detail
```

Tag dibatasi dan dinormalisasi oleh legacy menjadi maksimal beberapa tag
pada saat creation.

------------------------------------------------------------------------

# 47. Blog Edit

``` text
Author
  ↓
Open blog
  ↓
Edit
  ↓
Check story ownership
  ↓
Update:
  ├── title
  ├── category
  ├── privacy
  ├── content
  ├── tags
  └── closed state
  ↓
Update timestamp
```

Administrator dapat melewati ownership check melalui role `3`.

------------------------------------------------------------------------

# 48. Blog Delete

``` text
Author/Admin
  ↓
Delete blog
  ↓
Validate story
  ↓
Check ownership/admin
  ↓
Delete story
  ↓
Delete related stream
  ↓
Delete photos
  ↓
Delete related tag records
```

------------------------------------------------------------------------

# 49. Comments

Member dengan permission comment dapat comment pada content.

Flow:

``` text
Member
  ↓
Open story
  ↓
Comment
  ↓
Check comment permission
  ↓
Check story exists
  ↓
Check story not closed
  ↓
Validate minimum content length
  ↓
Create comment
  ↓
Increment comment counter
  ↓
Update last reply
  ↓
Optional vote/digg/dugg
```

Jika story closed:

``` text
Comment
  ↓
Rejected: topic closed
```

------------------------------------------------------------------------

# 50. Delete Comment

Legacy menyediakan delete comment pada story.

Permission berasal dari:

``` text
comment_write
```

dan Administrator mendapatkan bypass karena role `3`.

------------------------------------------------------------------------

# 51. Photos

Module:

``` text
photos
```

Feature:

-   create album;
-   upload photos;
-   add description;
-   manage photos;
-   delete photos;
-   album listing;
-   gallery view;
-   comments;
-   stream activity;
-   personal/following/friends/community views.

------------------------------------------------------------------------

# 52. Photo Album Flow

``` text
Member
  ↓
Photos
  ↓
Create Album
  ↓
Album Name
  ↓
Destination Page
  ↓
Check Page Access
  ↓
Create story with app=photos
  ↓
Upload photos
  ↓
Resize original
  ↓
Create thumbnail
  ↓
Create story_photos records
  ↓
Update album thumbnail
  ↓
Update photo count
  ↓
Update activity stream
```

------------------------------------------------------------------------

# 53. Photo Delete

``` text
Owner/Admin
  ↓
Manage Album
  ↓
Delete Photo
  ↓
Delete DB record
  ↓
Delete original file
  ↓
Delete thumbnail
  ↓
Select replacement thumbnail if available
  ↓
Decrease photo counter
```

------------------------------------------------------------------------

# 54. Videos

Module:

``` text
videos
```

Feature:

-   add video;
-   YouTube URL;
-   video description;
-   tags;
-   rating;
-   favorite;
-   profile listing;
-   following/friends listing;
-   public/community listing.

Video legacy primarily uses YouTube ID.

Flow:

``` text
Member
  ↓
Add Video
  ↓
Enter YouTube URL
  ↓
Extract YouTube ID
  ↓
Validate ID
  ↓
Create story
  ↓
Store video metadata
  ↓
Create stream
  ↓
Display video
```

------------------------------------------------------------------------

# 55. Music

Module:

``` text
music
```

Feature:

-   upload song;
-   MP3 validation;
-   own song / other musician;
-   musician name;
-   optional picture;
-   tags;
-   rating;
-   activity feed.

Flow:

``` text
Member
  ↓
Upload Song
  ↓
Select:
  ├── My own
  └── From another musician
  ↓
If other:
  musician name required
  ↓
Validate .mp3
  ↓
Upload file
  ↓
Optional picture
  ↓
Create story
  ↓
Create activity
```

------------------------------------------------------------------------

# 56. Events

Module:

``` text
events
```

Feature:

-   create event;
-   event date;
-   event time;
-   location;
-   join event;
-   leave event;
-   display joined members;
-   activity feed.

Flow create:

``` text
Member
  ↓
Create Event
  ↓
Select date
  ↓
Select time
  ↓
Input location
  ↓
Create story
  ↓
Add creator to joined-member list
```

Join:

``` text
Member
  ↓
Open event
  ↓
I want join
  ↓
Load joined member list
  ↓
If not already joined
  ↓
Append member ID
```

Leave:

``` text
Member
  ↓
Leave event
  ↓
Remove member ID
```

Legacy event membership is stored as serialized user IDs in the story
data.

------------------------------------------------------------------------

# 57. Favorites / Liked Content

Legacy memiliki konsep liked stream dan favorite option pada beberapa
content type.

User profile memiliki tab:

``` text
Liked
```

Flow:

``` text
Member
  ↓
Like/Favorite content
  ↓
Create relationship
  ↓
Liked content muncul pada profile
```

Administrator dapat melakukan moderation pada content tertentu.

------------------------------------------------------------------------

# 58. Groups

Group adalah community Page bertipe:

``` text
type = group
```

Group memiliki:

-   owner/creator;
-   members;
-   posts;
-   visibility;
-   membership policy;
-   logo;
-   description;
-   pending members.

------------------------------------------------------------------------

# 59. Create Group

General Member dapat membuat group.

Flow:

``` text
Member
  ↓
Groups
  ↓
Create Group
  ↓
Validate URI
  ├── minimum length
  ├── maximum length
  ├── alphanumeric
  └── uniqueness
  ↓
Input group name
  ↓
Select visibility
  ├── Public
  └── Private
  ↓
Select membership
  ├── Free to join
  └── Need approval
  ↓
Description
  ↓
Captcha
  ↓
Create Page(type=group)
  ↓
Create membership for creator
  ↓
Group created
```

------------------------------------------------------------------------

# 60. Group Visibility

### Public Group

``` text
Guest/Member
    ↓
Can discover group
    ↓
Can view group
```

### Private Group

``` text
Non-member
    ↓
Restricted access
```

Detail visibility harus diverifikasi saat migration karena beberapa
access checks berada di Page/group layer.

------------------------------------------------------------------------

# 61. Group Membership --- Free Join

``` text
Member
  ↓
Join Group
  ↓
Check already member
  ↓
Captcha
  ↓
Insert page_users
  ↓
Member active
```

------------------------------------------------------------------------

# 62. Group Membership --- Approval

``` text
Member
  ↓
Join Group
  ↓
Group requires approval
  ↓
Captcha
  ↓
Create pending membership
  ↓
Notify group owner
  ↓
Status = Pending
```

Owner kemudian:

``` text
Group Owner
  ↓
Pending Members
  ↓
Select member
  ↓
Approve
   OR
Ignore
```

Approve:

``` text
Delete pending
  ↓
Create page_users
  ↓
Send approval notification
```

Ignore:

``` text
pending.ignored = 1
```

------------------------------------------------------------------------

# 63. Group Owner

Group owner adalah creator:

``` text
pages.uid
```

Owner dapat:

-   edit group;
-   change visibility;
-   change membership policy;
-   edit description;
-   upload logo;
-   manage members;
-   approve pending members;
-   delete member;
-   delete group.

------------------------------------------------------------------------

# 64. Remove Group Member

Flow:

``` text
Owner
  ↓
Manage Members
  ↓
Select Member
  ↓
Confirm removal
  ↓
Delete member posts
  ↓
Delete member stories
  ↓
Delete membership
  ↓
Create ignored membership record
```

Legacy secara eksplisit menghapus content member di group ketika member
dikeluarkan.

------------------------------------------------------------------------

# 65. Leave Group

Member dapat leave group.

Namun creator tidak dapat leave group miliknya sendiri.

Flow:

``` text
Member
  ↓
Leave Group
  ↓
Confirm
  ↓
Delete member's group posts
  ↓
Delete member's group stories
  ↓
Delete membership
```

------------------------------------------------------------------------

# 66. Delete Group

Group owner atau Administrator dapat menghapus group.

Flow:

``` text
Owner/Admin
  ↓
Delete Group
  ↓
Confirmation
  ↓
Delete Page
  ↓
Delete stories
  ↓
Delete photos
  ↓
Delete tags relation
  ↓
Delete streams
  ↓
Delete memberships
```

------------------------------------------------------------------------

# 67. Pages

Page adalah entity bertipe:

``` text
type = page
```

Page dapat digunakan sebagai community/public identity.

Member dapat:

-   create Page;
-   edit Page;
-   edit logo;
-   like Page;
-   unlike Page;
-   view members/fans;
-   publish content pada Page sesuai access rule.

------------------------------------------------------------------------

# 68. Create Page

Flow:

``` text
Member
  ↓
Pages
  ↓
Create
  ↓
Validate URI
  ↓
Input Page Name
  ↓
Description
  ↓
Captcha
  ↓
Create page
  ↓
Owner assigned
```

------------------------------------------------------------------------

# 69. Manage Page

Owner dapat:

-   edit name;
-   edit description;
-   edit logo;
-   delete Page.

Administrator juga dapat bypass ownership pada operasi tertentu.

------------------------------------------------------------------------

# 70. Like Page

Flow:

``` text
Member
  ↓
Page
  ↓
Like
  ↓
Create page_users relationship
```

Unlike:

``` text
Member
  ↓
Unlike
  ↓
Delete page_users relationship
```

Page membership/fan list kemudian digunakan untuk menampilkan jumlah dan
daftar fans.

------------------------------------------------------------------------

# 71. User Wall

Setiap user memiliki personal Page bertipe:

``` text
u
```

Profile wall:

``` text
/u/{username}
```

Personal wall digunakan sebagai destination untuk activity/content.

Member dapat:

-   melihat wall;
-   melihat activity;
-   comment;
-   publish content ke wall sesuai ownership;
-   melihat following;
-   melihat friends;
-   melihat groups;
-   melihat pages;
-   block;
-   follow;
-   add friend;
-   message.

------------------------------------------------------------------------

# 72. Profile Wall Privacy

Privacy menentukan apakah visitor dapat melihat profile/wall.

``` text
Everyone
Friends of friends
Friends only
```

Owner selalu dapat melihat profile sendiri.

------------------------------------------------------------------------

# 73. Member Report

Member dapat melaporkan URL/content.

Flow:

``` text
Member
  ↓
Report
  ↓
Input message
  ↓
Input report URL
  ↓
Submit
  ↓
Create reports record
  ↓
Administrator reviews
```

------------------------------------------------------------------------

# 74. Administrator --- ROLE 3

## 74.1 Definisi

Administrator adalah role `3`.

Admin module secara eksplisit menjalankan:

``` text
do_auth(3)
```

sehingga hanya Administrator yang dapat masuk Admin Panel.

Selain itu, `allow_access()` memperlakukan role `3` sebagai bypass
permission.

------------------------------------------------------------------------

# 75. Administrator --- Admin Panel

Entry:

``` text
/admin
```

Flow:

``` text
Administrator
  ↓
Admin Panel
  ↓
Load dashboard
```

Dashboard menampilkan:

-   JCow version;
-   total members;
-   pending members;
-   total reports;
-   unread reports;
-   management tools;
-   addon tools.

------------------------------------------------------------------------

# 76. Administrator --- Member Management

Admin dapat:

``` text
/admin/users
```

Fungsi:

-   melihat member;
-   filter/search member;
-   membuka user detail;
-   edit user;
-   disable user;
-   change role;
-   featured status;
-   password management;
-   delete user.

------------------------------------------------------------------------

# 77. Administrator --- Edit User

Flow:

``` text
Admin
  ↓
Members
  ↓
Select User
  ↓
User Edit
  ↓
Update:
  ├── account status
  ├── roles
  ├── featured
  └── password
  ↓
Save
```

Legacy mencegah perubahan tertentu terhadap Administrator utama.

Source menunjukkan user dengan role Administrator tidak dapat
diperlakukan sama seperti member biasa dalam beberapa management
operation.

------------------------------------------------------------------------

# 78. Administrator --- User Roles

Menu:

``` text
/admin/userroles
```

Admin dapat:

-   melihat roles;
-   membuat role;
-   edit role;
-   delete custom role.

Role dengan ID:

``` text
1–9
```

diperlakukan sebagai protected/system roles pada delete logic.

Role custom yang dibuat kemudian dapat digunakan sebagai permission
group.

------------------------------------------------------------------------

# 79. Administrator --- Permission Management

JCow memiliki permission system yang dapat mengizinkan feature kepada
role tertentu.

Contoh:

``` text
permission_add
permission_comment
permission_upload
permission_browse
permission_feed
```

Flow:

``` text
Admin
  ↓
Permissions
  ↓
Select feature
  ↓
Select allowed roles
  ↓
Save
  ↓
Feature authorization berubah
```

------------------------------------------------------------------------

# 80. Administrator --- Site Configuration

Menu:

``` text
/admin/config
```

Area konfigurasi meliputi:

### General

-   Site name;
-   Site slogan;
-   Site keywords;
-   webmaster email;
-   footer message.

### Privacy

-   public/private network;
-   invitation-only;
-   account verification;
-   privacy behavior.

### Social/Posting

-   posting limits;
-   permissions;
-   feature availability.

### Modules

-   groups enabled;
-   forums enabled;
-   music enabled;
-   optional applications.

### Appearance/Ads

-   theme;
-   sidebar;
-   ad visibility;
-   ad blocks.

------------------------------------------------------------------------

# 81. Administrator --- Module Management

Menu:

``` text
/admin/modules
```

Admin dapat mengaktifkan/nonaktifkan module.

Module yang terlihat dalam installation antara lain:

``` text
blogs
photos
videos
browse
feed
dashboard
account
admin
u
member
follow
forumadmin
friends
jquery
language
message
notifications
preference
report
rss
search
invite
```

Beberapa optional module juga ada:

``` text
events
music
groups
pages
```

------------------------------------------------------------------------

# 82. Administrator --- Menu Management

Admin dapat mengelola menu aplikasi.

Legacy menu menyimpan:

-   name;
-   tab name;
-   weight;
-   path;
-   app;
-   active status;
-   type;
-   protected;
-   allowed roles;
-   icon;
-   parent.

Jenis menu:

``` text
community
personal
tab
```

Flow:

``` text
Admin
  ↓
Menu
  ↓
Create/Edit/Reorder
  ↓
Set access
  ↓
Save
  ↓
Frontend navigation berubah
```

------------------------------------------------------------------------

# 83. Administrator --- Theme Management

Menu:

``` text
/admin/themes
```

Admin dapat mengelola theme.

Theme memengaruhi:

-   template;
-   CSS;
-   layout;
-   visual presentation.

Modernization target:

``` text
Theme legacy
   ↓
UI/UX redesign
   ↓
Laravel Blade/View
```

------------------------------------------------------------------------

# 84. Administrator --- Blocks

Admin dapat mengelola block content.

Block digunakan untuk area layout seperti:

-   top;
-   bottom;
-   sidebar;
-   ad area;
-   custom content.

------------------------------------------------------------------------

# 85. Administrator --- Custom Profile Fields

Menu:

``` text
/admin/customfields
```

Legacy mendukung sampai:

``` text
var1 ... var7
```

yang dapat dikonfigurasi menjadi:

-   text;
-   textarea;
-   select box;
-   disabled.

Admin menentukan:

-   field label;
-   description;
-   value/options;
-   required;
-   field type.

Member kemudian mengisi field tersebut pada profile.

------------------------------------------------------------------------

# 86. Administrator --- Text Management

Menu:

``` text
/admin/texts
```

Digunakan untuk mengubah content/text konfigurasi.

Contoh text configuration:

-   locations;
-   rules;
-   footer;
-   custom text;
-   module text.

------------------------------------------------------------------------

# 87. Administrator --- Translation

Menu:

``` text
/admin/translate
```

Admin dapat mengelola translation resources.

Flow:

``` text
Admin
  ↓
Translate
  ↓
Select language/text
  ↓
Edit translation
  ↓
Save
```

------------------------------------------------------------------------

# 88. Administrator --- Reports

Menu:

``` text
/admin/reports
```

Flow:

``` text
Member submits report
  ↓
reports table
  ↓
Admin Panel
  ↓
Reports
  ↓
Mark reports as read
  ↓
Review:
  ├── reporter
  ├── URL
  ├── message
  └── created time
```

Legacy ketika membuka report page menandai report sebagai read.

------------------------------------------------------------------------

# 89. Administrator --- Feature / Content Moderation

Administrator memiliki privilege untuk:

-   edit content milik user;
-   delete content;
-   delete comments;
-   feature content;
-   unfeature content;
-   manage users;
-   manage groups/pages pada ownership-protected area;
-   mengakses user management dari profile.

Pada story layer, feature/unfeature secara eksplisit menggunakan role:

``` text
9
```

bukan role `3`.

Ini menunjukkan legacy sebenarnya memiliki permission/role granularity
di luar tiga default role.

Untuk project modernization, business rule ini harus dipetakan ulang
secara sadar, bukan di-hardcode sebagai angka.

------------------------------------------------------------------------

# 90. Administrator --- Feature Story

Flow:

``` text
Admin/authorized role
  ↓
Open story
  ↓
Feature
  ↓
stories.featured = 1
  ↓
Content masuk featured area
```

Unfeature:

``` text
featured = 0
```

------------------------------------------------------------------------

# 91. Administrator --- Member Approval

Jika account verification mode menggunakan administrator approval:

``` text
Signup
  ↓
Account disabled/pending
  ↓
Admin Members
  ↓
Review member
  ↓
Approve / activate
```

Pending account tidak dapat menggunakan seluruh fitur normal.

------------------------------------------------------------------------

# 92. Administrator --- Disable Account

Admin dapat mengubah status user.

Flow:

``` text
Admin
  ↓
User Edit
  ↓
Disabled = 1
  ↓
User access restricted
```

System session check membaca status disabled dan dapat mengarahkan user
ke:

-   membership page;
-   verification page;
-   pending approval notice.

------------------------------------------------------------------------

# 93. Administrator --- Delete User

Delete user adalah operasi high-impact.

Secara business intent:

``` text
Admin
  ↓
Delete User
  ↓
Remove account
```

Namun karena database legacy tidak memiliki FK, cascade behavior harus
diperiksa per module.

**Untuk Laravel modernization, operasi ini harus dibuat sebagai explicit
domain deletion policy.**

Jangan mengandalkan database legacy behavior.

------------------------------------------------------------------------

# 94. Administrator --- SQL Management

Admin legacy memiliki fitur:

``` text
/admin/jsql
```

yang memungkinkan execute SQL query.

Ini merupakan administrative power sangat tinggi.

Untuk Laravel 13 modernization:

> **Fitur ini sebaiknya TIDAK direplikasi sebagai public/admin web
> feature kecuali memang merupakan requirement eksplisit dari
> stakeholder.**

Lebih aman menggunakan:

-   migrations;
-   database CLI;
-   controlled maintenance commands;
-   audited admin actions.

------------------------------------------------------------------------

# 95. Administrator --- Module Enable/Disable

Flow:

``` text
Admin
  ↓
Modules
  ↓
Select module
  ↓
Enable/Disable
  ↓
Update module state
  ↓
Application menu/feature berubah
```

------------------------------------------------------------------------

# 96. Administrator --- Theme / Ads

Admin dapat mengontrol:

-   theme;
-   CSS;
-   ad blocks;
-   ad visibility berdasarkan role.

Konfigurasi:

``` text
hide_ad_roles
```

digunakan untuk menyembunyikan advertisement kepada role tertentu.

Default legacy menunjukkan Administrator dapat diset sebagai role yang
tidak melihat ads.

------------------------------------------------------------------------

# 97. Administrator --- System Update

Legacy mempunyai update module.

Konsep:

``` text
Admin
  ↓
Update
  ↓
Check version/update metadata
  ↓
Run update mechanism
```

Dalam Laravel modernization, fungsi ini sebaiknya digantikan dengan:

``` text
Git
+
Composer
+
Laravel migrations
+
CI/CD
```

bukan self-update source code melalui web.

------------------------------------------------------------------------

# 98. Cross-Role Feature Matrix

  Feature                         Guest            General Member        Administrator
  ------------------------ ------------------- ---------------------- -------------------
  Visit public website              ✓                    ✓                     ✓
  Login                             ✓                    ✓                     ✓
  Signup                            ✓                    \-                    ✓
  Browse members                    ✓                    ✓                     ✓
  View public profile               ✓                    ✓                     ✓
  Edit own profile                 \-                    ✓                     ✓
  Upload avatar                    \-                    ✓                     ✓
  Privacy settings                 \-                    ✓                     ✓
  Follow user                      \-                    ✓                     ✓
  Unfollow                         \-                    ✓                     ✓
  View followers            public/permission            ✓                     ✓
  Add friend                       \-                    ✓                     ✓
  Approve friend request           \-                    ✓                     ✓
  Reject friend request            \-                    ✓                     ✓
  Block user                       \-                    ✓                     ✓
  Send PM                          \-                    ✓                     ✓
  Inbox                            \-                    ✓                     ✓
  Outbox                           \-                    ✓                     ✓
  News feed                   configurable               ✓                     ✓
  Create blog                      \-                    ✓                     ✓
  Edit own blog                    \-                    ✓                     ✓
  Delete own blog                  \-                    ✓                     ✓
  Comment                          \-                    ✓                     ✓
  Upload photo                     \-                    ✓                     ✓
  Create album                     \-                    ✓                     ✓
  Add video                        \-                    ✓                     ✓
  Upload music                     \-                    ✓                     ✓
  Create event                     \-                    ✓                     ✓
  Join event                       \-                    ✓                     ✓
  Create group                     \-                    ✓                     ✓
  Join group                       \-                    ✓                     ✓
  Create Page                      \-                    ✓                     ✓
  Like Page                        \-                    ✓                     ✓
  Report content                   \-                    ✓                     ✓
  Manage users                     \-                    \-                    ✓
  Manage roles                     \-                    \-                    ✓
  Manage permissions               \-                    \-                    ✓
  Manage modules                   \-                    \-                    ✓
  Manage menu                      \-                    \-                    ✓
  Manage themes                    \-                    \-                    ✓
  Site configuration               \-                    \-                    ✓
  Custom profile fields            \-                    \-                    ✓
  Manage reports                   \-                    \-                    ✓
  Feature content                  \-           ownership/permission   ✓/authorized role
  Execute SQL                      \-                    \-                ✓ legacy

> Tabel di atas adalah **baseline business matrix**, sedangkan
> permission individual legacy dapat dikustomisasi oleh Administrator.
> Jadi "✓" berarti tersedia secara normal menurut source/configuration,
> bukan jaminan bahwa semua deployment JCow identik.

------------------------------------------------------------------------

# 99. End-to-End Business Flow

## 99.1 Visitor → Member

``` text
Guest
  ↓
Website
  ↓
Browse
  ↓
Signup
  ↓
Validation
  ↓
Captcha / Rules
  ↓
Account Created
  ↓
Verification?
 ├── No
 │    ↓
 │  Active
 │
 ├── Email
 │    ↓
 │  Verification Code
 │    ↓
 │  Active
 │
 └── Admin Approval
      ↓
    Pending
      ↓
    Admin approves
      ↓
    Active
  ↓
Login
  ↓
Dashboard
```

------------------------------------------------------------------------

# 100. End-to-End Social Flow

``` text
Member A
  ↓
Browse Member
  ↓
Open Member B
  ├── Follow
  ├── Add Friend
  ├── Message
  └── Block
```

Follow:

``` text
A → B
```

Friend:

``` text
A → request → B
             ↓
          approve
             ↓
           A ↔ B
```

------------------------------------------------------------------------

# 101. End-to-End Content Flow

``` text
Member
  ↓
Select module
  ├── Blog
  ├── Photo
  ├── Video
  ├── Music
  └── Event
  ↓
Select destination
  ├── Own wall
  ├── Group
  └── Page
  ↓
Check permission
  ↓
Check destination membership/ownership
  ↓
Create story
  ↓
Create attachment
  ↓
Create tags
  ↓
Create stream
  ↓
Display
  ↓
Other members:
  ├── View
  ├── Comment
  ├── Vote
  ├── Like/Favorite
  └── Report
```

------------------------------------------------------------------------

# 102. End-to-End Group Flow

``` text
Member
  ↓
Create Group
  ↓
Set:
  ├── Public/Private
  └── Free/Approval
  ↓
Group Created
  ↓
Creator automatically member
  ↓
Other Member
  ↓
Join
  ├── Free → member
  └── Approval → pending
                  ↓
               Owner review
                ├── Approve
                └── Ignore
  ↓
Member posts content
  ↓
Group stream
```

------------------------------------------------------------------------

# 103. End-to-End Moderation Flow

``` text
Member sees problematic content
        ↓
Report
        ↓
reports
        ↓
Administrator
        ↓
Review report
        ↓
Open target URL
        ↓
Determine action
        ├── Ignore
        ├── Delete content
        ├── Delete comment
        ├── Disable member
        ├── Manage member
        └── Other administrative action
```

------------------------------------------------------------------------

# 104. Data Domain Mapping

## Identity

``` text
accounts
roles
pages(type=u)
```

## Social Graph

``` text
friends
friend_reqs
followers
blacks
liked
favorites
```

## Content

``` text
stories
story_comments
story_photos
story_categories
story_cat_groups
tags
tag_ids
votes
```

## Activity

``` text
streams
```

## Groups

``` text
pages(type=group)
page_users
group_members_pending
```

## Pages

``` text
pages(type=page)
page_users
```

## Communication

``` text
messages
messages_sent
notifications/messages(from_id=0)
```

## Moderation

``` text
reports
banned
blacklist
```

## Configuration

``` text
gvar
text
menu
modules
roles
```

------------------------------------------------------------------------

# 105. Business Rule yang Wajib Dipertahankan Saat Migrasi

## Authentication

-   User harus authenticated untuk fitur member.
-   Account disabled/pending tidak boleh menggunakan semua feature.
-   Verification mode memengaruhi account activation.
-   Guest dan member mempunyai access level berbeda.

## Profile

-   User dapat mengatur profile privacy.
-   Owner selalu memiliki akses terhadap profile sendiri.
-   Privacy dapat bergantung pada friendship.

## Social

-   User tidak dapat add dirinya sendiri.
-   User tidak boleh membuat duplicate follow.
-   Friendship disimpan dua arah pada legacy.
-   Friend request harus dihapus ketika accepted/rejected.
-   Block memengaruhi interaction tertentu.

## Content

-   Hanya role yang memiliki `permission_add` dapat membuat story.
-   Hanya user yang memiliki akses terhadap Page yang dapat posting.
-   Personal Page hanya dapat digunakan owner.
-   Content yang closed tidak dapat menerima comment.
-   Owner dapat edit/delete content.
-   Administrator dapat bypass ownership pada beberapa operation.
-   Tag memiliki limit pada legacy.
-   Content creation dapat membuat stream activity.

## Groups

-   Group mempunyai visibility.
-   Group mempunyai membership policy.
-   Group owner otomatis menjadi member.
-   Approval group menggunakan pending membership.
-   Owner dapat approve/ignore pending request.
-   Owner tidak dapat leave group miliknya.
-   Remove member dapat menghapus content member dalam group.

## Messaging

-   Message membutuhkan recipient valid.
-   Block dapat mencegah PM.
-   Inbox/outbox merupakan data terpisah pada legacy.
-   Read status harus dipertahankan.

## Moderation

-   Member dapat membuat report.
-   Administrator dapat melihat report.
-   Opening report menandai report read.
-   Administrator memiliki elevated access.

------------------------------------------------------------------------

# 106. Legacy Technical Behavior yang Harus Diwaspadai

## 106.1 Role Bukan Sekadar Database Role

Legacy menggunakan:

``` text
roles = "2|11"
```

dan kemudian mengubahnya menjadi array.

Modern Laravel sebaiknya:

``` text
users
roles
role_user
```

dengan policy/permission yang jelas.

------------------------------------------------------------------------

## 106.2 Administrator adalah Bypass

Legacy:

``` text
allow_access()
```

menganggap role `3` sebagai full access.

Dalam Laravel:

``` text
Gate / Policy
```

sebaiknya memodelkan:

``` text
Administrator
    ↓
super-admin ability
```

secara eksplisit.

------------------------------------------------------------------------

## 106.3 Ownership dan Permission Berbeda

Contoh:

``` text
Edit story
```

membutuhkan:

``` text
permission
+
owner
```

Administrator dapat bypass.

Jadi jangan hanya membuat:

``` php
$this->authorize('update', $story);
```

tanpa memahami rule legacy.

------------------------------------------------------------------------

## 106.4 Privacy Bukan Role

Profile privacy:

``` text
Everyone
Friends of friends
Friends only
```

merupakan relationship policy.

Ini harus menjadi:

``` text
Policy / Service
```

bukan role permission.

------------------------------------------------------------------------

# 107. Suggested Laravel Domain Mapping

Untuk target Laravel 13 dengan Layered Monolith:

``` text
app/
├── Http/
│   └── Controllers/
│
├── Services/
│
├── Repositories/
│
├── Models/
│
├── Policies/
│
└── ViewModels/
```

Domain utama:

``` text
Authentication
Accounts
Profiles
Social
Content
Blogs
Photos
Videos
Music
Events
Groups
Pages
Messaging
Notifications
Reports
Administration
```

------------------------------------------------------------------------

# 108. Controller Responsibility

Controller hanya:

``` text
Request
  ↓
Validation
  ↓
Service
  ↓
Response/View
```

Contoh:

``` text
BlogController
    ↓
BlogService
    ↓
BlogRepository
```

Jangan memindahkan SQL legacy secara mentah ke Controller.

------------------------------------------------------------------------

# 109. Service Responsibility

Service memegang business logic.

Contoh:

``` text
FriendService
```

menangani:

``` text
sendRequest()
approveRequest()
rejectRequest()
removeFriend()
```

`approveRequest()` harus melakukan:

``` text
Validate request
    ↓
Create friendship
    ↓
Remove request
    ↓
Create notification
    ↓
Create activity
```

Jika menggunakan Laravel transaction:

``` text
DB::transaction()
```

semua perubahan dapat diperlakukan sebagai satu business operation.

------------------------------------------------------------------------

# 110. Repository Responsibility

Repository bertanggung jawab atas persistence/query.

Contoh:

``` text
FriendRepository
```

method:

``` text
findRequest()
existsFriendship()
createFriendship()
deleteRequest()
```

Service tidak perlu tahu SQL detail.

------------------------------------------------------------------------

# 111. Policy Responsibility

Policy menangani authorization yang context-sensitive.

Contoh:

``` text
StoryPolicy
```

method:

``` text
view()
create()
update()
delete()
comment()
```

Rule:

``` text
Administrator → allow
Owner → allow
Other member → deny
```

ditambah privacy/permission sesuai kebutuhan.

------------------------------------------------------------------------

# 112. View Responsibility

View hanya menangani presentation.

Legacy banyak menghasilkan HTML langsung dari PHP.

Target Laravel:

``` text
Controller
   ↓
Service
   ↓
Repository
   ↓
Model
   ↓
View
```

View tidak melakukan business logic.

------------------------------------------------------------------------

# 113. Migration Priority

Untuk modernization, business priority yang disarankan:

### P0 --- Identity

``` text
Guest
Login
Signup
Users
Profiles
Roles
```

### P1 --- Social Core

``` text
Friends
Friend Requests
Followers
Blocks
```

### P2 --- Content

``` text
Feed
Stories
Comments
Blogs
Photos
Videos
```

### P3 --- Community

``` text
Groups
Pages
Events
Music
```

### P4 --- Communication

``` text
Messages
Notifications
Email preferences
```

### P5 --- Administration

``` text
Members
Roles
Permissions
Reports
Configuration
Modules
Menus
Themes
```

------------------------------------------------------------------------

# 114. Acceptance Criteria untuk Legacy Behavior

Migration dianggap business-compatible apabila:

## Guest

-   dapat membuka public page;
-   dapat browse member sesuai configuration;
-   dapat signup;
-   tidak dapat melakukan member action.

## General Member

-   dapat login;
-   dapat mengelola profile;
-   dapat follow;
-   dapat friend;
-   dapat messaging;
-   dapat membuat content;
-   dapat comment;
-   dapat upload media;
-   dapat membuat/join group;
-   dapat membuat Page;
-   dapat report.

## Administrator

-   dapat login ke Admin Panel;
-   dapat manage member;
-   dapat manage roles;
-   dapat manage permissions;
-   dapat manage modules;
-   dapat manage menu;
-   dapat configure site;
-   dapat review reports;
-   dapat melakukan moderation;
-   dapat bypass ownership pada operation yang memang diberi bypass
    legacy.

------------------------------------------------------------------------

# 115. Hal yang Tidak Boleh Diasumsikan

Source code menunjukkan beberapa behavior legacy yang harus dianalisis
lebih lanjut sebelum migration final:

1.  `role 9` digunakan untuk feature/unfeature story, tetapi role
    default database hanya sampai `3`.
2.  Ada custom role seperti `11` pada permission configuration.
3.  `var1-var7` digunakan berbeda oleh module berbeda.
4.  Event member disimpan sebagai serialized array.
5.  Notification legacy disimpan sebagai message HTML/text.
6.  Stream memakai `app` dan `aid` untuk menghubungkan activity dengan
    module content.
7.  Friendship legacy disimpan dua arah.
8.  Group membership memiliki active dan pending table terpisah.
9.  Inbox dan sent message menggunakan tabel berbeda.
10. Legacy memiliki beberapa optional module yang dapat
    diaktifkan/nonaktifkan.

Karena itu, **target ERD Laravel tidak boleh dibuat hanya dari nama
tabel**. Source code business behavior harus menjadi sumber kebenaran.

------------------------------------------------------------------------

# 116. Final Business Architecture Legacy

Secara konseptual JCow legacy dapat dipahami seperti ini:

``` text
                         JCOW
                          │
             ┌────────────┼────────────┐
             │            │            │
           GUEST        MEMBER       ADMIN
             │            │            │
             │            │            └──── Admin Panel
             │            │                     │
             │            │          ┌──────────┼─────────┐
             │            │          │          │         │
             │            │       Users      Roles     Config
             │            │          │          │         │
             └────────────┼──────────┴──────────┴─────────┘
                          │
                    SOCIAL GRAPH
                          │
             ┌────────────┼────────────┐
             │            │            │
          Friends       Follow       Block
             │
             ▼
                       CONTENT
                          │
       ┌─────────┬────────┼────────┬─────────┐
       │         │        │        │         │
      Blog     Photo    Video    Music     Event
       │         │        │        │         │
       └─────────┴────────┼────────┴─────────┘
                          │
                       Stories
                          │
                ┌─────────┼─────────┐
                │         │         │
             Comments    Likes    Ratings
                          │
                       Activity
                          │
                         Feed
                          │
              ┌───────────┴───────────┐
              │                       │
            Groups                  Pages
              │                       │
           Members                  Fans
              │                       │
              └───────────┬───────────┘
                          │
                    COMMUNICATION
                          │
                ┌─────────┴─────────┐
                │                   │
             Messages          Notifications
                │
                ▼
             Reports
                │
                ▼
          Administrator
```

------------------------------------------------------------------------

# 117. Kesimpulan

JCow bukan sekadar aplikasi CRUD social media sederhana.

Business model legacy-nya terdiri dari:

``` text
Identity
+
Role/Permission
+
Privacy
+
Social Graph
+
Content
+
Community
+
Communication
+
Moderation
+
Administration
```

Tiga role utama adalah:

``` text
Guest
General Member
Administrator
```

tetapi authorization legacy sebenarnya memiliki kombinasi:

``` text
Role
+
Permission
+
Ownership
+
Privacy
+
Membership
+
System State
```

Oleh karena itu, saat convert ke Laravel 13:

> **Jangan memetakan `role` langsung menjadi "boleh/tidak boleh".**

Contoh:

``` text
General Member
    ↓
Edit Blog
    ↓
Boleh?
    ↓
Permission + Owner
```

atau:

``` text
General Member
    ↓
Post to Group
    ↓
Boleh?
    ↓
Permission
+
Group Membership
+
Group State
```

atau:

``` text
Guest
    ↓
View Profile
    ↓
Boleh?
    ↓
Public Access
+
Profile Privacy
```

Dengan kata lain, business rule legacy yang paling penting untuk dibawa
ke Laravel adalah:

``` text
                    AUTHORIZATION
                         │
       ┌─────────────────┼─────────────────┐
       ▼                 ▼                 ▼
      ROLE          PERMISSION         OWNERSHIP
       │                 │                 │
       └─────────────────┼─────────────────┘
                         ▼
                      PRIVACY
                         │
                         ▼
                    RELATIONSHIP
                         │
                         ▼
                  BUSINESS ACTION
```

Struktur inilah yang sebaiknya menjadi dasar
`Policy + Service + Repository + Controller + View` pada Laravel 13.

------------------------------------------------------------------------

# 118. Source Files yang Menjadi Basis Reverse Engineering

File utama yang dianalisis:

``` text
includes/libs/common.inc.php
includes/libs/ss.inc.php
includes/libs/member.module.php
includes/libs/account.module.php
includes/libs/admin.module.php
includes/libs/friends.module.php
includes/libs/follow.module.php
includes/libs/message.module.php
includes/libs/notifications.module.php
includes/libs/story.inc.php
includes/libs/u.module.php
includes/libs/browse.module.php

modules/blogs/blogs.php
modules/photos/photos.php
modules/videos/videos.php
modules/music/music.php
modules/events/events.php
modules/groups/groups.php
modules/group/group.php
modules/pages/pages.php
modules/page/page.php
modules/feed/feed.php
modules/report/report.php

install/data.sql
```

## Important

Dokumen ini adalah **Legacy Business Flow Baseline**.

Dokumen ini belum menjadi:

``` text
Laravel PRD
```

dan belum menjadi:

``` text
Laravel ERD final
```

Urutan pekerjaan yang benar adalah:

``` text
Legacy Source
     ↓
Business Flow ← dokumen ini
     ↓
Feature Inventory
     ↓
Business Rule Matrix
     ↓
Target Domain Model
     ↓
Target ERD
     ↓
Laravel 13 PRD
     ↓
Layered Architecture
     ↓
Migration Plan
     ↓
Implementation
```
