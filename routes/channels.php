<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('group.{groupId}', function ($user, $groupId) {
    $group = \App\Models\Group::withoutGlobalScopes()->find((int) $groupId);
    if (!$group) {
        return false;
    }
    if ($user->isAdmin() || (int) $group->uid === (int) $user->id) {
        return true;
    }
    if ($group->type !== 'private_group') {
        return true;
    }
    return $group->members()->where('uid', $user->id)->exists();
});

Broadcast::channel('online', function ($user) {
    return [
        'id' => (int) $user->id,
        'fullname' => $user->fullname ?? '',
        'username' => $user->username ?? '',
        'avatar_url' => $user->avatar_url ?? asset('assets/img/default.png'),
    ];
});
