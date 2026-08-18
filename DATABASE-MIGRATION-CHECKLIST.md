# Database Migration Checklist

Migration validation document for JCow → Laravel 13.

---

## USER

| Check | Status | Notes |
|-------|--------|-------|
| [ ] All accounts migrated | ⬜ | |
| [ ] Username unique | ⬜ | |
| [ ] Email unique | ⬜ | |
| [ ] Password valid | ⬜ | Legacy MD5 needs migration strategy |
| [ ] Profile migrated | ⬜ | |
| [ ] Role migrated | ⬜ | |
| [ ] Settings migrated | ⬜ | JSON in `settings` column |

---

## SOCIAL

| Check | Status | Notes |
|-------|--------|-------|
| [ ] Friend requests migrated | ⬜ | |
| [ ] Friendships migrated | ⬜ | Legacy bidirectional → single record |
| [ ] Follows migrated | ⬜ | |
| [ ] Likes migrated | ⬜ | |
| [ ] Comments migrated | ⬜ | Polymorphic: target_id + stream_id |

---

## FEED / STREAM

| Check | Status | Notes |
|-------|--------|-------|
| [ ] Posts migrated | ⬜ | From jcow_streams |
| [ ] Comments on posts | ⬜ | |
| [ ] Likes on posts | ⬜ | |

---

## MESSAGING

| Check | Status | Notes |
|-------|--------|-------|
| [ ] Inbox messages | ⬜ | jcow_messages |
| [ ] Sent messages | ⬜ | jcow_messages_sent |
| [ ] Chatrooms | ⬜ | jcow_chatrooms |
| [ ] Notifications | ⬜ | Separate from messages |

---

## GROUPS

| Check | Status | Notes |
|-------|--------|-------|
| [ ] Groups migrated | ⬜ | |
| [ ] Members migrated | ⬜ | Includes pending status |
| [ ] Topics migrated | ⬜ | |
| [ ] Posts migrated | ⬜ | |
| [ ] Polls migrated | ⬜ | |

---

## FORUM

| Check | Status | Notes |
|-------|--------|-------|
| [ ] Forums migrated | ⬜ | |
| [ ] Threads migrated | ⬜ | |
| [ ] Posts migrated | ⬜ | |
| [ ] Attachments | ⬜ | |
| [ ] Polls migrated | ⬜ | |
| [ ] Subscriptions | ⬜ | |

---

## CONTENT / STORIES

| Check | Status | Notes |
|-------|--------|-------|
| [ ] Stories migrated | ⬜ | |
| [ ] Photos migrated | ⬜ | |
| [ ] Tags migrated | ⬜ | |
| [ ] Categories | ⬜ | |

---

## PAGES

| Check | Status | Notes |
|-------|--------|-------|
| [ ] Pages migrated | ⬜ | |
| [ ] Page users | ⬜ | |

---

## SYSTEM

| Check | Status | Notes |
|-------|--------|-------|
| [ ] Reports migrated | ⬜ | |
| [ ] Banned users | ⬜ | |
| [ ] Invites migrated | ⬜ | |

---

## VALIDATION QUERIES (Run Against Legacy DB)

```sql
-- Comments orphan check
SELECT c.* FROM jcow_comments c
LEFT JOIN jcow_accounts u ON u.id = c.uid
WHERE u.id IS NULL;

-- Friends orphan check
SELECT f.* FROM jcow_friends f
LEFT JOIN jcow_accounts u1 ON u1.id = f.user_id
LEFT JOIN jcow_accounts u2 ON u2.id = f.friend_id
WHERE u1.id IS NULL OR u2.id IS NULL;

-- Followers orphan check
SELECT f.* FROM jcow_followers f
LEFT JOIN jcow_accounts u1 ON u1.id = f.uid
LEFT JOIN jcow_accounts u2 ON u2.id = f.fid
WHERE u1.id IS NULL OR u2.id IS NULL;

-- Stream orphan check
SELECT s.* FROM jcow_streams s
LEFT JOIN jcow_accounts u ON u.id = s.uid
WHERE u.id IS NULL;

-- Likes orphan check
SELECT l.* FROM jcow_liked l
LEFT JOIN jcow_accounts u ON u.id = l.uid
LEFT JOIN jcow_streams s ON s.id = l.stream_id
WHERE u.id IS NULL OR s.id IS NULL;

-- Messages orphan check
SELECT m.* FROM jcow_messages m
LEFT JOIN jcow_accounts u1 ON u1.id = m.from_id
LEFT JOIN jcow_accounts u2 ON u2.id = m.to_id
WHERE u1.id IS NULL OR u2.id IS NULL;

-- Group members orphan check
SELECT gm.* FROM jcow_group_members gm
LEFT JOIN jcow_groups g ON g.id = gm.gid
LEFT JOIN jcow_accounts u ON u.id = gm.uid
WHERE g.id IS NULL OR u.id IS NULL;

-- Forum posts orphan check
SELECT fp.* FROM jcow_forum_posts fp
LEFT JOIN jcow_forum_threads ft ON ft.id = fp.tid
LEFT JOIN jcow_accounts u ON u.id = fp.uid
WHERE ft.id IS NULL OR u.id IS NULL;

-- Story orphan check
SELECT s.* FROM jcow_stories s
LEFT JOIN jcow_accounts u ON u.id = s.uid
LEFT JOIN jcow_story_categories sc ON sc.id = s.cid
WHERE u.id IS NULL OR sc.id IS NULL;
```

---

## ROW COUNT COMPARISON

| Legacy Table | Legacy Count | Laravel Table | Laravel Count | Match? |
|--------------|--------------|---------------|---------------|--------|
| jcow_accounts | | users | | |
| jcow_friends | | friendships | | Normalized |
| jcow_followers | | follows | | |
| jcow_streams | | posts/activities | | |
| jcow_comments | | comments | | |
| jcow_liked | | likes | | |
| jcow_messages | | messages | | |
| jcow_groups | | groups | | |
| jcow_forums | | forums | | |
| jcow_stories | | stories | | |

> **Note:** Counts may differ due to normalization (e.g., friendships deduplicated from 2→1 records).

---

## BUSINESS VALIDATION

| Scenario | Legacy Result | Laravel Query | Expected |
|----------|---------------|---------------|----------|
| User 1 friends User 3 | 2 records (1→3, 3→1) | `friendships where (user_id=1 AND friend_id=3) OR (user_id=3 AND friend_id=1)` | 1 record |
| User 1 follows User 3 | 1 record (1→3) | `follows where follower_id=1 AND following_id=3` | 1 record |
| User 1 likes Post 5 | 1 record | `likes where user_id=1 AND likeable_type=Post AND likeable_id=5` | 1 record |
| Group member | 1 record | `group_members where group_id=X AND user_id=Y` | 1 record |

---

## ORPHAN RECORD REPORT

Run `php artisan jcow:check-orphans --db=jcow --report=storage/app/orphan-report.txt`

Record output here:

```
[Paste orphan report output]
```

---

## SIGN-OFF

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Tech Lead | | | |
| Database Owner | | | |
| QA | | | |