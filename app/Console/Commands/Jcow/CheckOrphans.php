<?php

namespace App\Console\Commands\Jcow;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckOrphans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jcow:check-orphans
                            {--db=jcow : Legacy JCow database name}
                            {--report=storage/app/orphan-report.txt : Output file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect orphan records in the legacy JCow database before migration (FK-free MyISAM)';

    private array $checks = [
        ['jcow_comments', 'uid', 'jcow_accounts', 'id'],
        ['jcow_comments', 'stream_id', 'jcow_streams', 'id'],
        ['jcow_friends', 'user_id', 'jcow_accounts', 'id'],
        ['jcow_friends', 'friend_id', 'jcow_accounts', 'id'],
        ['jcow_friend_reqs', 'uid', 'jcow_accounts', 'id'],
        ['jcow_friend_reqs', 'fid', 'jcow_accounts', 'id'],
        ['jcow_followers', 'uid', 'jcow_accounts', 'id'],
        ['jcow_followers', 'fid', 'jcow_accounts', 'id'],
        ['jcow_blacks', 'uid', 'jcow_accounts', 'id'],
        ['jcow_blacks', 'bid', 'jcow_accounts', 'id'],
        ['jcow_streams', 'uid', 'jcow_accounts', 'id'],
        ['jcow_liked', 'uid', 'jcow_accounts', 'id'],
        ['jcow_liked', 'stream_id', 'jcow_streams', 'id'],
        ['jcow_messages', 'from_id', 'jcow_accounts', 'id'],
        ['jcow_messages', 'to_id', 'jcow_accounts', 'id'],
        ['jcow_messages_sent', 'from_id', 'jcow_accounts', 'id'],
        ['jcow_messages_sent', 'to_id', 'jcow_accounts', 'id'],
        ['jcow_chatrooms', 'uid', 'jcow_accounts', 'id'],
        ['jcow_chatrooms', 'fid', 'jcow_accounts', 'id'],
        ['jcow_groups', 'creatorid', 'jcow_accounts', 'id'],
        ['jcow_group_members', 'gid', 'jcow_groups', 'id'],
        ['jcow_group_members', 'uid', 'jcow_accounts', 'id'],
        ['jcow_group_posts', 'gid', 'jcow_groups', 'id'],
        ['jcow_group_posts', 'uid', 'jcow_accounts', 'id'],
        ['jcow_forum_threads', 'fid', 'jcow_forums', 'id'],
        ['jcow_forum_threads', 'userid', 'jcow_accounts', 'id'],
        ['jcow_forum_posts', 'tid', 'jcow_forum_threads', 'id'],
        ['jcow_forum_posts', 'uid', 'jcow_accounts', 'id'],
        ['jcow_stories', 'uid', 'jcow_accounts', 'id'],
        ['jcow_stories', 'stream_id', 'jcow_streams', 'id'],
        ['jcow_story_photos', 'sid', 'jcow_stories', 'id'],
        ['jcow_pages', 'uid', 'jcow_accounts', 'id'],
        ['jcow_page_users', 'pid', 'jcow_pages', 'id'],
        ['jcow_page_users', 'uid', 'jcow_accounts', 'id'],
        ['jcow_reports', 'uid', 'jcow_accounts', 'id'],
        ['jcow_invites', 'uid', 'jcow_accounts', 'id'],
        ['jcow_role_user', 'user_id', 'jcow_accounts', 'id'],
        ['jcow_role_user', 'role_id', 'jcow_roles', 'id'],
    ];

    public function handle(): int
    {
        $db = $this->option('db');
        $totalOrphans = 0;
        $report = [];

        $this->info("Scanning legacy database: {$db}");

        foreach ($this->checks as [$table, $col, $refTable, $refCol]) {
            if (!DB::select("SELECT 1 FROM information_schema.tables WHERE table_schema = ? AND table_name = ?", [$db, $table])) {
                continue;
            }

            $count = DB::selectOne(
                "SELECT COUNT(*) AS c FROM {$db}.{$table} t
                 LEFT JOIN {$db}.{$refTable} r ON r.{$refCol} = t.{$col}
                 WHERE r.{$refCol} IS NULL AND t.{$col} IS NOT NULL"
            )->c ?? 0;

            if ($count > 0) {
                $line = "ORPHAN: {$table}.{$col} -> {$refTable}.{$refCol} : {$count} rows";
                $this->warn($line);
                $report[] = $line;
                $totalOrphans += $count;
            } else {
                $this->line("OK:     {$table}.{$col}");
            }
        }

        $summary = "\nTOTAL ORPHAN RECORDS: {$totalOrphans}\n";
        $this->info($summary);

        if ($this->option('report')) {
            file_put_contents($this->option('report'), implode("\n", $report) . $summary);
            $this->info("Report written to " . $this->option('report'));
        }

        return self::SUCCESS;
    }
}
