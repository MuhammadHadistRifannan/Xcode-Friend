Siap. Saya revisi **PRD yang kamu upload**, bukan membuat PRD baru dari nol. Bagian scope, timeline 12 minggu, pembagian 6 orang, migration, milestone, dan workflow yang sudah ada tetap dipertahankan; yang saya ubah terutama **arsitektur target dan pembagian responsibility supaya benar-benar mengikuti Layered Monolith: Controller → Service → Repository → Model, dengan Controller → View**. PRD lama memang sudah punya Service Layer, tetapi Repository Layer belum menjadi bagian eksplisit dari arsitektur target. 

# PRD — Modernisasi JCow Social Network ke Laravel 13

## Revised Architecture & Team Implementation Plan

---

# 1. Project Overview

| Item            | Detail                                 |
| --------------- | -------------------------------------- |
| Project         | JCow Social Network Modernization      |
| Tipe            | Legacy System Modernization            |
| Existing System | JCow PHP Native                        |
| Target System   | Laravel 13                             |
| Architecture    | **Layered Monolithic Architecture**    |
| Database        | MySQL                                  |
| View            | Laravel Blade                          |
| Backend Team    | Hadist, Daffa                          |
| Frontend Team   | Akmal, Giska                           |
| UI/UX Team      | Bima, Ifan                             |
| Team Size       | 6 orang                                |
| Duration        | ± 12 minggu / 3 bulan                  |
| Working Days    | 5 hari kerja/minggu                    |
| Working Style   | Fleksibel                              |
| Main Objective  | Migrasi fitur JCow + modernisasi UI/UX |

Target 12 minggu tetap menggunakan scope prioritas karena dokumen sebelumnya juga menilai seluruh module JCow tidak realistis untuk dijanjikan selesai sempurna dalam tiga bulan. 

---

# 2. Product Objective

Modernisasi JCow dilakukan dengan dua tujuan utama:

### 2.1 Technical Modernization

Mengubah implementasi PHP Native legacy menjadi Laravel 13 dengan struktur:

```text
Controller
     │
     ▼
 Service
     │
     ▼
Repository
     │
     ▼
  Model
     │
     ▼
 Database
```

### 2.2 UI/UX Modernization

Memperbarui:

* visual interface;
* layout;
* responsive design;
* navigation;
* interaction;
* loading state;
* error state;
* empty state;
* accessibility.

Jadi output proyek bukan sekadar:

> "JCow dipindah ke Laravel."

Tetapi:

> **"JCow dimodernisasi secara teknis dan visual."**

---

# 3. Problem Statement

JCow saat ini merupakan aplikasi PHP Native legacy yang memiliki banyak module dan dependency legacy.

Dokumen sebelumnya mencatat sekitar **113 PHP files dan 51 tabel**, sehingga proses modernisasi harus dilakukan dengan feature inventory, database inventory, module inventory, dan prioritas P0/P1/P2. 

Masalah utama:

1. Business logic sulit dipisahkan.
2. Data access tersebar.
3. Maintainability rendah.
4. UI legacy.
5. Pengembangan fitur membutuhkan pemahaman terhadap kode lama.
6. Risiko migration data tinggi.
7. Banyak feature berpotensi menyebabkan scope creep.

---

# 4. Product Scope

## P0 — Core Social Network

P0 menjadi prioritas utama.

### Authentication

* Register
* Login
* Logout
* Session
* Password
* Forgot Password
* Reset Password

### User

* User profile
* Edit profile
* Avatar
* User settings

### Social

* Post
* Edit post
* Delete post
* Feed
* Comment
* Like
* Friend request
* Accept
* Reject
* Unfriend
* Follow

### Communication

* Notification
* Messaging
* Conversation

### Data

* Users
* Profiles
* Posts
* Comments
* Friends
* Followers
* Notifications
* Messages
* Media

P0 tersebut konsisten dengan migration acceptance criteria pada PRD sebelumnya. 

---

# 5. P1 — Secondary Features

Jika P0 sudah stabil:

* Groups
* Group members
* Group posts
* Events
* Event members
* Search

PRD sebelumnya menempatkan Groups, Events, dan Search pada Week 8 sebagai **P1 minimal functional**. 

---

# 6. P2 — Backlog

Feature yang tidak critical terhadap core social network masuk backlog.

Contoh:

```text
P2
├── Advanced recommendation
├── Advanced analytics
├── Complex real-time system
└── Non-critical legacy feature
```

Aturan:

```text
P0 belum selesai
       ↓
P2 tidak dikerjakan
```

Ini mempertahankan scope-control rule dari PRD sebelumnya. 

---

# 7. Architecture

## 7.1 Architecture Decision

**Layered Monolithic Architecture**

Aplikasi tetap satu Laravel application dan satu deployment unit.

```text
                    INTERNET
                       │
                       ▼
                    NGINX
                       │
                       ▼
              ┌────────────────┐
              │   Laravel 13   │
              │    Monolith    │
              └───────┬────────┘
                      │
        ┌─────────────┴──────────────┐
        │                            │
        ▼                            ▼
   Controller                     Blade View
        │
        ▼
     Service
        │
        ▼
   Repository
        │
        ▼
      Model
        │
        ▼
      MySQL
```

---

# 8. Layer Responsibility

## 8.1 Controller

Controller bertanggung jawab terhadap:

* HTTP request;
* menerima Form Request;
* memanggil Service;
* menentukan response;
* redirect;
* return View.

### Controller tidak boleh:

```text
SQL Query              ❌
Business Logic         ❌
Complex Transaction    ❌
Data Transformation besar ❌
```

Contoh:

```php
public function store(StorePostRequest $request)
{
    $post = $this->postService->create(
        auth()->user(),
        $request->validated()
    );

    return redirect()
        ->route('posts.show', $post);
}
```

---

# 9. Service Layer

Service merupakan **business layer**.

Tanggung jawab:

* business rules;
* application logic;
* orchestration;
* transaction;
* koordinasi repository;
* authorization logic yang bersifat business;
* trigger notification/job.

Contoh:

```text
PostService
├── Create Post
├── Update Post
├── Delete Post
└── Publish Post
```

Service boleh memanggil:

```text
Repository
```

tetapi tidak melakukan query database secara langsung.

---

# 10. Repository Layer

Ini merupakan perubahan arsitektur utama dari PRD sebelumnya.

Repository menjadi abstraction untuk data access.

```text
Service
   │
   ▼
Repository Interface
   │
   ▼
Repository Implementation
   │
   ▼
Eloquent Model
```

Contoh:

```php
interface PostRepositoryInterface
{
    public function create(array $data): Post;

    public function findById(int $id): ?Post;

    public function update(Post $post, array $data): bool;

    public function delete(Post $post): bool;
}
```

Implementasi:

```php
class PostRepository implements PostRepositoryInterface
{
    public function create(array $data): Post
    {
        return Post::create($data);
    }
}
```

### Repository bertanggung jawab:

* query;
* filtering;
* pagination;
* persistence;
* retrieval.

### Repository tidak bertanggung jawab:

```text
Business Rule       ❌
HTTP Response       ❌
View                ❌
Authorization Flow  ❌
```

---

# 11. Model Layer

Model menggunakan Eloquent.

Tanggung jawab:

* database representation;
* relationship;
* casts;
* scopes;
* attributes.

Contoh:

```text
User
├── posts()
├── comments()
├── friendships()
├── followers()
└── conversations()
```

Model tidak menjadi tempat business logic besar.

---

# 12. View Layer

Laravel Blade digunakan sebagai presentation layer.

```text
resources/views/

layouts/
components/
auth/
users/
posts/
comments/
friends/
messages/
notifications/
groups/
admin/
```

View hanya bertugas:

```text
Receive Data
     ↓
Render UI
```

Tidak boleh:

```text
View → Database       ❌
View → Repository     ❌
View → Service        ❌
```

---

# 13. Target Folder Structure

Struktur target diubah menjadi:

```text
app/
│
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── User/
│   │   ├── Post/
│   │   ├── Comment/
│   │   ├── Friendship/
│   │   ├── Message/
│   │   ├── Notification/
│   │   ├── Group/
│   │   └── Admin/
│   │
│   ├── Requests/
│   │   ├── Auth/
│   │   ├── User/
│   │   ├── Post/
│   │   └── ...
│   │
│   └── Middleware/
│
├── Models/
│   ├── User.php
│   ├── Profile.php
│   ├── Post.php
│   ├── Comment.php
│   ├── Friendship.php
│   ├── Follow.php
│   ├── Conversation.php
│   ├── Message.php
│   └── Notification.php
│
├── Services/
│   ├── Auth/
│   ├── User/
│   ├── Social/
│   ├── Messaging/
│   ├── Notification/
│   └── Migration/
│
├── Repositories/
│   ├── Contracts/
│   │   ├── UserRepositoryInterface.php
│   │   ├── PostRepositoryInterface.php
│   │   ├── CommentRepositoryInterface.php
│   │   ├── FriendshipRepositoryInterface.php
│   │   └── MessageRepositoryInterface.php
│   │
│   └── Eloquent/
│       ├── UserRepository.php
│       ├── PostRepository.php
│       ├── CommentRepository.php
│       ├── FriendshipRepository.php
│       └── MessageRepository.php
│
├── Policies/
├── Notifications/
├── Jobs/
│
├── Console/
│   └── Commands/
│       └── Jcow/
│
└── Providers/
    └── RepositoryServiceProvider.php
```

---

# 14. Architecture Rules

Semua anggota wajib mengikuti dependency berikut:

```text
Controller
    │
    ▼
Service
    │
    ▼
Repository Interface
    │
    ▼
Repository
    │
    ▼
Model
    │
    ▼
Database
```

Controller dapat mengembalikan:

```text
Controller → View
```

Tidak boleh:

```text
Controller → DB                  ❌
Controller → Model query         ❌
Controller → Repository          ❌
View → DB                        ❌
View → Service                   ❌
Repository → Business Logic      ❌
```

---

# 15. Team Structure

| Person     | Position         | Main Responsibility                           |
| ---------- | ---------------- | --------------------------------------------- |
| **Hadist** | Tech Lead + BE 2 | Architecture, Service, Integration, Migration |
| **Daffa**  | BE 1             | Model, Repository, Database                   |
| **Akmal**  | FE 1             | Blade, Layout, Core UI                        |
| **Giska**  | FE 2             | Social Interaction, Feature UI                |
| **Bima**   | UI/UX 1          | Visual Design System                          |
| **Ifan**   | UI/UX 2          | UX Flow & Responsive                          |

---

# 16. Hadist — Tech Lead + BE 2

### Primary responsibility

```text
Architecture
Service Layer
Integration
Migration
Code Review
Technical Decision
```

### Detailed responsibility

* Laravel architecture;
* Service architecture;
* Repository architecture review;
* critical business logic;
* migration architecture;
* migration commands;
* integration;
* performance review;
* security review;
* pull request review;
* technical documentation.

### Important

Hadist **tidak menjadi bottleneck coding**.

PRD sebelumnya sudah menekankan bahwa Tech Lead harus menjadi technical decision maker, bukan mengerjakan seluruh pekerjaan tim. 

---

# 17. Daffa — BE 1

### Primary responsibility

```text
Database
Model
Repository
```

### Detailed responsibility

* migration;
* schema;
* Eloquent models;
* relationships;
* repository interface implementation;
* query optimization;
* database validation;
* data integrity;
* backend testing.

Daffa menjadi **owner utama Data Access Layer**.

---

# 18. Akmal — FE 1

### Primary responsibility

```text
Blade
Layout
Core Pages
```

### Detailed responsibility

* Laravel Blade;
* layout;
* reusable component;
* authentication UI;
* profile;
* settings;
* core page;
* frontend integration.

---

# 19. Giska — FE 2

### Primary responsibility

```text
Social Interaction
Feature Integration
```

### Detailed responsibility

* Feed interaction;
* Post;
* Comment;
* Like;
* Friendship;
* Notification;
* Messaging;
* frontend state;
* AJAX/fetch interaction.

---

# 20. Bima — UI/UX 1

### Primary responsibility

```text
Visual Design
```

### Detailed responsibility

* Design System;
* typography;
* color;
* spacing;
* components;
* desktop design;
* visual consistency;
* design QA.

---

# 21. Ifan — UI/UX 2

### Primary responsibility

```text
User Experience
```

### Detailed responsibility

* user flow;
* information architecture;
* interaction;
* responsive behavior;
* mobile UX;
* usability;
* accessibility;
* UAT UX validation.

---

# 22. Revised 12-Week Development Plan

## WEEK 1 — Discovery & Architecture

### Hadist

* Laravel architecture design;
* Layered Monolith specification;
* Controller/Service/Repository rules;
* Git strategy;
* coding convention;
* legacy architecture audit.

### Daffa

* database inventory;
* 51-table audit;
* relationship mapping;
* schema proposal.

### Akmal

* Blade architecture;
* page inventory;
* component inventory.

### Giska

* legacy interaction audit;
* frontend behavior inventory.

### Bima

* UI audit;
* design system initial draft.

### Ifan

* user flow audit;
* UX problem identification.

### Deliverables

```text
Feature Inventory
Database Inventory
Module Inventory
Architecture Proposal
UI/UX Audit
User Flow
Git Strategy
Coding Convention
```

Deliverable tersebut mempertahankan output Week 1 dari PRD sebelumnya. 

---

# WEEK 2 — Laravel Foundation

### Hadist

* Laravel 13;
* environment;
* Docker;
* MySQL;
* legacy DB connection;
* Git branch;
* CI/CD;
* logging;
* error handling;
* RepositoryServiceProvider;
* Service convention.

### Daffa

* migrations;
* Model foundation;
* relationship convention;
* Repository contracts;
* first repository implementation.

### Akmal

* Blade;
* main layout;
* asset structure;
* components.

### Giska

* JS interaction;
* AJAX/fetch convention.

### Bima

* design system finalization.

### Ifan

* UX flow finalization.

### Output

```text
Laravel 13
├── Layered Architecture
├── MySQL
├── Legacy DB
├── Blade
├── Repository
├── Service
├── CI/CD
└── Design System
```

---

# WEEK 3 — Authentication

### Hadist

* Auth Service;
* legacy password strategy;
* security review.

### Daffa

* User Model;
* UserRepository;
* authentication data access;
* migration.

### Akmal

* Login;
* Register;
* Forgot Password;
* Reset Password.

### Giska

* validation state;
* loading state;
* error state.

### Bima

* authentication design.

### Ifan

* authentication UX.

### Acceptance

```text
Register
   ↓
Login
   ↓
Dashboard
   ↓
Logout
```

---

# WEEK 4 — User & Profile

### Hadist

* User Service;
* authorization;
* Policy;
* integration review.

### Daffa

* Profile Model;
* ProfileRepository;
* relationships;
* migration.

### Akmal

* Dashboard;
* Profile;
* Edit Profile;
* Avatar.

### Giska

* profile interaction;
* settings interaction.

### Bima

* Profile design.

### Ifan

* Profile UX;
* responsive.

---

# WEEK 5 — Posts & Feed

### Hadist

* PostService;
* FeedService;
* business rules;
* transaction;
* architecture review.

### Daffa

* PostRepository;
* Feed query;
* Post Model;
* pagination.

### Akmal

* Feed;
* Post Card;
* Create Post.

### Giska

* Post interaction;
* Comment UI;
* Like.

### Bima

* Post component.

### Ifan

* Feed UX.

### Acceptance

```text
Create Post
View Feed
Edit Post
Delete Post
Pagination
```

PRD awal juga menempatkan Post, Feed, CRUD, pagination, dan authorization pada Week 5. 

---

# WEEK 6 — Comments, Likes & Friendship

### Hadist

* CommentService;
* FriendshipService;
* business rules.

### Daffa

* CommentRepository;
* FriendshipRepository;
* relationships.

### Akmal

* Comment;
* Like;
* Friend List.

### Giska

* Friend Request;
* Accept;
* Reject;
* Unfriend;
* Follow.

### Bima

* interaction states.

### Ifan

* social interaction UX.

---

# WEEK 7 — Notification & Messaging

### Hadist

* NotificationService;
* MessageService;
* read/unread;
* integration.

### Daffa

* Conversation Model;
* Message Model;
* ConversationRepository;
* MessageRepository.

### Akmal

* Notification component;
* Navbar integration.

### Giska

* Message list;
* Conversation;
* Send Message.

### Bima

* Notification system.

### Ifan

* Messaging UX.

Acceptance:

```text
User A
  ↓
Send Message
  ↓
User B
  ↓
Notification
  ↓
Read
```

Scope ini mengikuti struktur Week 7 pada PRD sebelumnya. 

---

# WEEK 8 — Groups, Events & Search

### Hadist

* Service architecture;
* integration.

### Daffa

* Models;
* repositories;
* relationships.

### Akmal

* Group detail;
* Event detail.

### Giska

* Groups;
* Events;
* Search.

### Bima

* Group component;
* Event component.

### Ifan

* Group UX;
* Search UX.

Target:

> **P1 minimal functional**

---

# WEEK 9 — Data Migration

Ini tetap menjadi **high-risk milestone**.

### Hadist — Migration Lead

Membuat migration command:

```bash
php artisan jcow:migrate
```

Dengan command spesifik:

```bash
php artisan jcow:migrate-users
php artisan jcow:migrate-posts
php artisan jcow:migrate-comments
php artisan jcow:migrate-friends
php artisan jcow:migrate-messages
php artisan jcow:migrate-media
```

### Daffa

* schema validation;
* relationship validation;
* foreign key;
* data integrity;
* repository compatibility.

### Akmal + Giska

* migrated user;
* migrated profile;
* migrated post;
* migrated media;
* functional verification.

### Bima + Ifan

* broken image;
* empty state;
* visual validation.

Migration:

```text
JCow DB
   ↓
Extract
   ↓
Transform
   ↓
Validate
   ↓
Load
   ↓
Laravel DB
```

Legacy DB hanya digunakan sebagai sumber migration, bukan dependency permanen. 

---

# WEEK 10 — Integration & Functional Parity

### Hadist

* architecture cleanup;
* Service review;
* Repository review;
* integration;
* security;
* performance.

### Daffa

* query optimization;
* N+1 detection;
* database bug fixing.

### Akmal

* core frontend fixes.

### Giska

* social feature fixes.

### Bima

* visual consistency.

### Ifan

* UX consistency.

Regression:

```text
Register
Login
Profile
Post
Comment
Like
Friend
Follow
Notification
Message
Group
Event
Search
Media
```

---

# WEEK 11 — QA & Hardening

**Tidak boleh menambah feature besar.**

### Backend

* security;
* validation;
* authorization;
* query optimization;
* error handling.

### Frontend

* responsive;
* browser compatibility;
* loading;
* error;
* empty states.

### UI/UX

* consistency;
* accessibility;
* typography;
* spacing;
* interaction.

### Hadist

```text
Code Review
Architecture Review
Security Review
Migration Review
Performance Review
```

Scope ini mempertahankan Week 11 dari PRD sebelumnya. 

---

# WEEK 12 — UAT, Deployment & Handover

### Hadist

* production deployment;
* final migration;
* backup;
* monitoring;
* rollback strategy.

### Daffa

* database validation;
* backend validation.

### Akmal

* frontend testing.

### Giska

* feature testing.

### Bima

* visual QA.

### Ifan

* UAT;
* UX verification;
* documentation.

---

# 23. Final Cutover

```text
JCow Production
       │
       ▼
 Final Backup
       │
       ▼
Final Migration
       │
       ▼
Data Validation
       │
       ▼
 Laravel 13
       │
       ▼
      UAT
       │
       ▼
 Production
```

JCow lama **tidak langsung dihapus**.

```text
JCow
 ↓
Read-only
 ↓
Archive
 ↓
Decommission
```

Hal ini juga sudah ditetapkan pada PRD awal. 

---

# 24. Milestone

| Milestone | Week | Target             |
| --------- | ---: | ------------------ |
| M1        |  1–2 | Foundation         |
| M2        |  3–4 | Auth + User        |
| M3        |  5–6 | Social Core        |
| M4        |  7–8 | Communication + P1 |
| M5        |    9 | Data Migration     |
| M6        |   10 | Integration        |
| M7        |   11 | QA                 |
| M8        |   12 | UAT + Production   |

Struktur milestone ini dipertahankan dari PRD sebelumnya. 

---

# 25. Definition of Done

Feature hanya dianggap selesai apabila:

```text
[ ] Requirement selesai
[ ] UI selesai
[ ] UX tervalidasi
[ ] Controller mengikuti convention
[ ] Business logic berada di Service
[ ] Data access melalui Repository
[ ] Model memiliki relationship yang benar
[ ] Validation menggunakan Form Request
[ ] Authorization tersedia
[ ] Error handling tersedia
[ ] Responsive
[ ] Tested
[ ] Pull Request dibuat
[ ] Code Review selesai
[ ] Integration test berhasil
```

---

# 26. Team Dependency

Alurnya:

```text
             UI/UX
           Bima + Ifan
               │
               ▼
           Frontend
        Akmal + Giska
               │
               ▼
            Backend
        Daffa + Hadist
               │
               ▼
           Integration
             Hadist
               │
               ▼
              QA
         Semua anggota
```

Tetapi ini **bukan berarti pekerjaan harus sequential**. PRD sebelumnya juga menetapkan bahwa pekerjaan dapat berjalan paralel berdasarkan dependency feature. 

Contoh:

```text
Bima ── Design Post ──┐
                      │
Ifan ── Post UX ──────┤
                      ▼
Akmal ── Post UI ─────┐
                      │
Giska ── Interaction ─┤
                      ▼
Daffa ── Repository ──┐
                      │
Hadist ── Service ────┘
```

---

# 27. Git Workflow

```text
main
 │
 └── develop
       │
       ├── feature/auth
       ├── feature/profile
       ├── feature/post
       ├── feature/friendship
       ├── feature/message
       ├── feature/migration
       │
       ├── ui/auth
       ├── ui/profile
       └── ui/feed
```

Flow:

```text
Feature Branch
      ↓
Pull Request
      ↓
Code Review
      ↓
develop
      ↓
Integration Test
      ↓
main
```

**Tidak ada direct push ke `main`.**

---

# 28. Daily Workflow

Standup 10–15 menit:

```text
1. Kemarin mengerjakan apa?
2. Hari ini mengerjakan apa?
3. Ada blocker?
```

Tidak perlu berubah menjadi rapat satu jam. PRD awal juga menetapkan format standup tersebut. 

---

# 29. Risk Management

| Risiko                   | Level     | Mitigasi                      |
| ------------------------ | --------- | ----------------------------- |
| Legacy DB kompleks       | 🔴 High   | Audit Week 1                  |
| Password legacy          | 🔴 High   | Migration strategy sejak awal |
| Relationship rusak       | 🔴 High   | Preserve ID + validation      |
| Migration terlambat      | 🔴 High   | Prototype Week 2              |
| Hadist jadi bottleneck   | 🔴 High   | Delegasi ke Daffa             |
| Backend bottleneck       | 🔴 High   | Repository ownership Daffa    |
| UI terlalu banyak revisi | 🟠 Medium | Design freeze                 |
| Scope terlalu besar      | 🔴 High   | P0/P1/P2                      |
| Bug akhir proyek         | 🟠 Medium | Continuous testing            |

Risk matrix ini mempertahankan risiko utama pada PRD sebelumnya, dengan tambahan penekanan bahwa Repository/Data Access menjadi ownership Daffa. 

---

# 30. Target Akhir

Pada akhir Week 12:

```text
                         INTERNET
                            │
                            ▼
                          NGINX
                            │
                            ▼
                 ┌────────────────────┐
                 │     Laravel 13     │
                 │      Monolith      │
                 └─────────┬──────────┘
                           │
                ┌──────────┴───────────┐
                │                      │
                ▼                      ▼
           Controllers               Blade
                │
                ▼
             Services
                │
                ▼
           Repositories
                │
                ▼
              Models
                │
                ▼
              MySQL
```

Database target mencakup domain utama:

```text
users
profiles
posts
comments
reactions
friendships
follows
notifications
conversations
messages
groups
group_members
events
event_members
media
```

Domain database tersebut juga sudah tercantum dalam PRD awal. 

---

# 31. Final Architecture Decision

**Architecture resmi proyek:**

> **Layered Monolithic Architecture menggunakan Laravel 13 dengan Controller, Service, Repository, Model, dan Blade View.**

### Responsibility final

```text
┌──────────────────────────────────┐
│             VIEW                 │
│        Blade / Presentation      │
└────────────────┬─────────────────┘
                 ▲
                 │
┌────────────────┴─────────────────┐
│          CONTROLLER              │
│        HTTP / Request             │
└────────────────┬─────────────────┘
                 │
                 ▼
┌──────────────────────────────────┐
│            SERVICE               │
│         Business Logic           │
└────────────────┬─────────────────┘
                 │
                 ▼
┌──────────────────────────────────┐
│          REPOSITORY              │
│          Data Access             │
└────────────────┬─────────────────┘
                 │
                 ▼
┌──────────────────────────────────┐
│             MODEL               │
│           Eloquent               │
└────────────────┬─────────────────┘
                 │
                 ▼
┌──────────────────────────────────┐
│             MySQL                │
└──────────────────────────────────┘
```

**Perubahan terpenting dari PRD lama:** sebelumnya arsitektur target mencantumkan `Controllers → Models → Services` tetapi belum mendefinisikan Repository sebagai layer eksplisit.  Sekarang **Repository menjadi layer resmi**, dengan Daffa sebagai owner utama Data Access Layer dan Hadist sebagai owner arsitektur/integrasi. Ini membuat pembagian kerja 2 Backend kalian jauh lebih jelas dan mengurangi kemungkinan semua query, business logic, dan integration numpuk di satu orang.
