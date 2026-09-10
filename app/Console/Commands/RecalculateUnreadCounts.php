<?php

namespace App\Console\Commands;

use App\Jobs\RecalculateUnreadCountsJob;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('unread:recalculate')]
#[Description('Recalculate unread notification & message counts and store them in cache.')]
class RecalculateUnreadCounts extends Command
{
    public function handle(): int
    {
        (new RecalculateUnreadCountsJob())->handle();

        $this->info('Unread counts recalculated and cached.');

        return self::SUCCESS;
    }
}
