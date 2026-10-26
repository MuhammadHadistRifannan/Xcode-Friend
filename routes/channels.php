<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('online', function ($user) {
    return [
        'id' => (int) $user->id,
        'fullname' => $user->fullname ?? '',
        'username' => $user->username ?? '',
        'avatar_url' => $user->avatar_url ?? asset('assets/img/default.png'),
    ];
});
