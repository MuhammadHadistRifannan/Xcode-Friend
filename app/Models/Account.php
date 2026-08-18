<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jcow_accounts';

    protected $fillable = [
        'fbid',
        'email',
        'lastact',
        'created',
        'username',
        'fullname',
        'password',
        'level',
        'points',
        'avatar',
        'signature',
        'blurbs',
        'profile_permission',
        'location',
        'lastlogin',
        'ipaddress',
        'chpass',
        'disabled',
        'intr',
        'gender',
        'about_me',
        'birthyear',
        'birthmonth',
        'birthday',
        'hide_age',
        'reg_code',
        'forum_posts',
        'featured',
        'roles',
        'country',
        'locale',
        'state',
        'jcowsess',
        'token',
        'wall_id',
        'followers',
        'settings',
        'var1',
        'var2',
        'var3',
        'var4',
        'var5',
        'var6',
        'var7',
        'pass',
        'hide_me',
    ];

    protected $hidden = [
        'password',
        'pass',
        'token',
        'chpass',
        'jcowsess',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'disabled' => 'boolean',
            'hide_me' => 'boolean',
            'hide_age' => 'boolean',
            'featured' => 'boolean',
            'level' => 'integer',
            'points' => 'integer',
            'gender' => 'integer',
            'profile_permission' => 'integer',
            'lastact' => 'integer',
            'lastlogin' => 'integer',
            'birthyear' => 'integer',
            'birthmonth' => 'integer',
            'birthday' => 'integer',
            'forum_posts' => 'integer',
            'followers' => 'integer',
            'wall_id' => 'integer',
        ];
    }

    // ── Social Graph ──

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'jcow_role_user', 'user_id', 'role_id');
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'jcow_friends', 'user_id', 'friend_id')
            ->withTimestamps();
    }

    public function friendOf(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'jcow_friends', 'friend_id', 'user_id')
            ->withTimestamps();
    }

    public function sentFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'uid');
    }

    public function receivedFriendRequests(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'fid');
    }

    public function followers(): HasMany
    {
        return $this->hasMany(Follower::class, 'fid');
    }

    public function following(): HasMany
    {
        return $this->hasMany(Follower::class, 'uid');
    }

    public function blacks(): HasMany
    {
        return $this->hasMany(Black::class, 'uid');
    }

    public function blackedBy(): HasMany
    {
        return $this->hasMany(Black::class, 'bid');
    }

    // ── Stream / Feed ──

    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class, 'uid');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'uid');
    }

    public function profileComments(): HasMany
    {
        return $this->hasMany(ProfileComment::class, 'uid');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Liked::class, 'uid');
    }

    // ── Messaging ──

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'to_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'from_id');
    }

    public function sentMessageCopies(): HasMany
    {
        return $this->hasMany(MessageSent::class, 'from_id');
    }

    public function chatrooms(): HasMany
    {
        return $this->hasMany(Chatroom::class, 'uid');
    }

    // ── Groups ──

    public function createdGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'creatorid');
    }

    public function groupMemberships(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'uid');
    }

    public function groupPosts(): HasMany
    {
        return $this->hasMany(GroupPost::class, 'uid');
    }

    // ── Forum ──

    public function forumThreads(): HasMany
    {
        return $this->hasMany(ForumThread::class, 'userid');
    }

    public function forumPosts(): HasMany
    {
        return $this->hasMany(ForumPost::class, 'uid');
    }

    // ── Stories ──

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class, 'uid');
    }

    // ── Pages ──

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'uid');
    }

    public function favoritePages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'jcow_page_users', 'uid', 'pid');
    }

    // ── Favorites ──

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'uid');
    }

    // ── System ──

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'uid');
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class, 'uid');
    }
}
