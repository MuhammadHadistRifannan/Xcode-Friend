<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\Eloquent\MessageRepository;
use App\Repositories\Contracts\FriendRepositoryInterface;
use App\Repositories\Eloquent\FriendRepository;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Eloquent\NotificationRepository;
use App\Repositories\Contracts\AccountRepositoryInterface;
use App\Repositories\Eloquent\AccountRepository;
use App\Repositories\Contracts\BlockRepositoryInterface;
use App\Repositories\Eloquent\BlockRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(FriendRepositoryInterface::class, FriendRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(BlockRepositoryInterface::class, BlockRepository::class);
    }
}
