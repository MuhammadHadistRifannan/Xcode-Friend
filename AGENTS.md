# AGENTS.md — Xcode-Friend (Laravel 13 JCow Modernization)

Compact instruction file for future OpenCode sessions. Every line answers:
"Would an agent likely miss this without help?"

---

## Quickstart

```bash
# 1. Setup environment
cp .env.example .env
# Edit .env: set DB_CONNECTION=mysql, DB_DATABASE=jcow_db
# Ensure PHP >= 8.3 (composer.json requires it)

# 2. Install dependencies
composer install
npm install
# Then: php artisan key:generate
# Then: npm run build

# 3. Generate app key then run migrations
php artisan migrate:fresh --seed  # use with CAUTION

# 4. Run data risk checks
php artisan jcow:check-orphans --db=jcow \
    --report=storage/app/orphan-report.txt

# 5. Serve
php artisan serve
# Visit http://localhost:8000
```

---

## Critical Environment

| Setting | Value | Notes |
|---------|-------|-------|
| PHP | >= 8.3 | composer.json requires `^8.3`. Currently running 8.2.12 will fail. |
| DB | MySQL 8+ | .env.example shows `jcow_db`. Migration checks reference legacy `jcow` DB. |
| AUTH_GUARD | web | Default. User model maps to `jcow_accounts` via Eloquent. |
| BCRYPT_ROUNDS | 12 | In .env.example. Legacy passwords are MD5 (32-char) — do NOT migrate raw. |

> **PHP version mismatch** is the #1 blocker. Composer will error if PHP < 8.3. Ensure `php -v` shows 8.3.x before running `composer install`.

---

## Model Foundation

Models map to `jcow_*` legacy tables. All use PHP 8 attributes (`#[Fillable]`, `#[Hidden]`) per PRD Laravel 13 convention.

Key models (see `app/Models/`):

- **Account** (`jcow_accounts`) — core user table, 47 columns. Has `roles()`, `friends()`, `streams()`, `comments()`, `likes()`, etc.
- **Role** (`jcow_roles`) — links to Account via `jcow_role_user` pivot.
- **Stream** (`jcow_streams`) — central activity/feed table. `user()` relationship to Account.
- **Comment** (`jcow_comments`) — polymorphic-ish: `target_id` + `stream_id`.
- **Liked** (`jcow_liked`) — `UNIQUE(user_id, stream_id)` to prevent double-likes.
- **FriendRequest** (`jcow_friend_reqs`) — `uid` → `fid`. Status: pending/accepted.
- **Follower** (`jcow_followers`) — directional: `uid` follows `fid`.
- **Black** (`jcow_blacks`) — blacklist: `uid` blocks `bid`.
- **Group** (`jcow_groups`) — creatorid links to Account. Has `members()`, `posts()`, `topics()`.
- **Forum** (`jcow_forums`) — hierarchical with `parent_id`. Threads → posts.
- **Story** (`jcow_stories`) — content with categories, photos, tags.
- **Page** (`jcow_pages`) — custom pages with `page_users` membership.
- **Report** (`jcow_reports`) — user reports with `hasread` flag.

> **Agent pitfall**: Do not treat `jcow_accounts` as a simple `users` table. It's a "God Table" with 47 columns mixing auth, profile, roles, settings, stats. PRD recommends splitting into `users` + `profiles` + `user_settings`, but current models map directly to `jcow_accounts` for fidelity to legacy data.

---

## Repository Pattern (Daffa — Owner Data Access)

Contracts in `app/Repositories/Contracts/`, implementations in `app/Repositories/Eloquent/`.

Register bindings in `app/Providers/RepositoryServiceProvider.php`.

Example interfaces:
- `UserRepositoryInterface` → `UserRepository` (Eloquent)
- `PostRepositoryInterface` → `PostRepository` (Eloquent)
- `FriendshipRepositoryInterface` → `FriendshipRepository` (Eloquent)
- `FollowRepositoryInterface` → `FollowRepository` (Eloquent)
- `MessageRepositoryInterface` → `MessageRepository` (Eloquent)
- `GroupRepositoryInterface` → `GroupRepository` (Eloquent)
- `ForumRepositoryInterface` → `ForumRepository` (Eloquent)
- `StoryRepositoryInterface` → `StoryRepository` (Eloquent)

> **Agent pitfall**: Do not query database directly from Controllers or Services. Use the Repository layer. Controller → Service → Repository → Model → Database is the enforced flow (PRD Architecture Rules).

---

## Service Layer (Hadist — Architecture Owner)

Business rules live in Services. Example shapes:

- `PostService` — `create()`, `update()`, `delete()`, `publish()`
- `FriendshipService` — `sendRequest()`, `accept()`, `reject()`, `unfriend()`
- `FollowService` — `follow()`, `unfollow()`, `isFollowing()`
- `MessageService` — `send()`, `receive()`, `markRead()`
- `GroupService` — `createGroup()`, `join()`, `leave()`, `createTopic()`

> **Agent pitfall**: Controller must NOT contain SQL queries, business logic, or complex transactions. Delegate everything to Services.

---

## Migration & Data Risk

Legacy JCow database has these characteristics (per DB.md & IMPLEMENT.md):

- **51 tables**, MyISAM engine, **zero foreign keys**
- `jcow_accounts` is a "God Table" with 47 columns — do NOT copy raw into Laravel
- `jcow_streams` is the central activity table (type/app/aid encode content source)
- Friendship is stored bidirectionally (two rows per friendship)
- Follow is directional (one row per follow)
- `jcow_messages` mixes notifications + legacy inbox
- `jcow_liked` needs `UNIQUE(user_id, stream_id)` constraint
- Legacy passwords are MD5 (32-char `password` + 32-char `pass`) — **do NOT migrate as-is**
- Counter columns (followers, members, posts, likes) are **denormalized** — not source of truth

### Commands

```bash
# Check for orphan records in legacy DB
php artisan jcow:check-orphans --db=jcow --report=storage/app/orphan-report.txt

# Full migration prototype (Week 2+)
php artisan jcow:migrate:prototype  # to be implemented per PRD Week 9

# Validate migration
php artisan migrate:status       # show which migrations ran
php artisan db:show              # show current DB schema
```

### Orphan Validation Queries

See `DATABASE-MIGRATION-CHECKLIST.md` for the full SQL validation queries. Key pattern:

```sql
SELECT COUNT(*) FROM jcow_comments c
LEFT JOIN jcow_accounts u ON u.id = c.uid
WHERE u.id IS NULL;  -- orphans = rows where user doesn't exist
```

> **Agent pitfall**: Do not assume FK integrity. Legacy has NO foreign keys. Validate data before adding FK constraints.

---

## Development Workflow

```bash
# Branch from develop
git checkout -b feature/xxxxx develop

# Work, commit frequently
git add -A
git commit -m "feat: ..."

# PR → code review (Hadist mandatory)
# Merge → develop

# Deploy
# No direct push to main
```

### Daily Standup (10–15 min)

1. Kemarin mengerjakan apa?
2. Hari ini mengerjakan apa?
3. Ada blocker?

---

## Folder Structure (PRD Target)

```
app/
├ Http/         ← Controllers + Requests
├ Models/       ← Eloquent models (this file)
├ Services/     ← Business logic
├ Repositories/ ← Contracts + Eloquent implementations
├ Providers/    ← RepositoryServiceProvider + others
└ Console/Commands/Jcow/  ← artisan commands
```

> **Agent pitfall**: Do not place business logic in Models. Models = Eloquent + relationships + scopes only. Service = business rules. Repository = data access.

---

## Constraints & Gotchas (verified from codebase)

| Issue | Source | Fix |
|-------|--------|-----|
| PHP < 8.3 blocks composer install | composer.json `^8.3` | Upgrade PHP or use compat wrappers |
| `jcow_accounts` has 47 columns | DB.md "God Table" | Map only needed fields; do NOT copy all |
| `jcow_streams.type`/`app`/`aid` semantics unclear | DB.md §12, §14 | Do NOT infer meaning without source code |
| `jcow_password`/`pass` are MD5 legacy | DB.md §36 | Do NOT migrate as hashed password |
| `jcow_friends` bidirectional | DB.md §12 | Normalize to single record with `user_one_id < user_two_id` |
| `jcow_followers` directional | DB.md §13 | Keep as `uid → fid` |
| Counter columns are denormalized | DB.md §35 | Use `COUNT()` on normalized tables, not legacy integers |
| `jcow_messages` mixes notification + inbox | DB.md §17, PRD §23 | Separate into `notifications` + `conversations` |
| Legacy uses MyISAM | DB.md §31 | Migration target is InnoDB + FKs |
| No FKs in legacy | DB.md §32 | Validate data, then add FKs in Laravel migrations |

---

## What to Avoid (speculative / fluff)

- Do NOT assume `var1`–`var7` on `jcow_accounts` have specific meanings without source code
- Do NOT assume `type` on `jcow_streams` means "post vs comment" without verifying
- Do NOT treat legacy `groups.members` / `groups.posts` / `groups.topics` as source of truth — they're cached counters
- Do NOT copy `jcow_accounts` directly as Laravel `users` table — split per PRD
- Do NOT add FK constraints to legacy DB in-place — create new Laravel DB first

---

## References (keep these, not in AGENTS.md but worth knowing)

- `DB.md` — 1921-line database analysis, logical ERD, business rules
- `IMPLEMENT.md` — 2000+ line implementation roadmap, migration strategy
- `PRD.md` — 1605-line Product Requirements, architecture, team structure
- `Flow.md` / `FlowBisnis.md` — process flows (not yet read in this session)
- `app/Models/User.php` — Laravel 13 attribute-based model metadata (`#[Fillable]`, `#[Hidden]`)
- `app/Providers/RepositoryServiceProvider.php` — new file created for this repo
- `database/migrations/2026_08_13_*.php` — 14 migration files, all `jcow_*` tables