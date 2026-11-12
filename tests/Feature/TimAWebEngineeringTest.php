<?php

namespace Tests\Feature;

use App\Events\MessageDeleted;
use App\Events\StreamCreated;
use App\Models\BlockedIp;
use App\Models\Stream;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TimAWebEngineeringTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Buat tabel minimal untuk pengujian isolasi di SQLite in-memory
        if (!Schema::hasTable('jcow_accounts')) {
            Schema::create('jcow_accounts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username')->default('');
                $table->string('password')->default('');
                $table->string('fullname')->nullable();
                $table->string('email')->nullable();
                $table->string('avatar')->nullable();
                $table->string('roles')->default('member');
                $table->integer('level')->default(0);
                $table->integer('created')->default(0);
                $table->integer('last_active')->default(0);
            });
        }

        if (!Schema::hasTable('jcow_friends')) {
            Schema::create('jcow_friends', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('uid');
                $table->integer('fid');
                $table->integer('approved')->default(1);
            });
        }

        if (!Schema::hasTable('jcow_blacks')) {
            Schema::create('jcow_blacks', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('uid');
                $table->integer('bid');
            });
        }

        if (!Schema::hasTable('jcow_pages')) {
            Schema::create('jcow_pages', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('uid')->default(1);
                $table->string('title')->default('Group');
                $table->string('type')->default('private_group');
            });
        }

        if (!Schema::hasTable('jcow_page_users')) {
            Schema::create('jcow_page_users', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('pid');
                $table->integer('uid');
                $table->integer('role')->default(1);
            });
        }

        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ai_security_logs')) {
            Schema::create('ai_security_logs', function (Blueprint $table) {
                $table->id();
                $table->timestamp('detected_at')->nullable();
                $table->string('attack_type', 150);
                $table->string('source_ip', 64)->nullable();
                $table->string('target', 500);
                $table->string('severity', 32)->default('medium');
                $table->longText('ai_analysis')->nullable();
                $table->string('status', 32)->default('open');
                $table->boolean('ip_blocked')->default(false);
                $table->json('raw_payload')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blocked_ips')) {
            Schema::create('blocked_ips', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address', 64)->unique();
                $table->string('reason', 255)->nullable();
                $table->unsignedBigInteger('blocked_by')->nullable();
                $table->unsignedBigInteger('ai_security_log_id')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function test_legacy_ajax_polling_routes_are_removed_and_message_deleted_uses_private_channel(): void
    {
        $this->assertFalse(Route::has('messages.poll'));
        $this->assertFalse(Route::has('messages.onlineStatus'));
        $this->assertFalse(Route::has('admin.pentest'));
        $this->assertFalse(Route::has('admin.pentest.app'));
        $this->assertTrue(Route::has('admin.ai-security-logs'));

        $event = new MessageDeleted(99, 1, 2, 'everyone');
        $channels = $event->broadcastOn();

        $this->assertCount(2, $channels);
        $this->assertInstanceOf(PrivateChannel::class, $channels[0]);
        $this->assertSame('private-user.1', $channels[0]->name);
        $this->assertSame('private-user.2', $channels[1]->name);
        $this->assertSame('message.deleted', $event->broadcastAs());
    }

    public function test_stream_created_privacy_scoping_prevents_private_and_friends_leaks(): void
    {
        DB::table('jcow_friends')->insert(['uid' => 10, 'fid' => 20, 'approved' => 1]);
        DB::table('jcow_pages')->insert(['id' => 5, 'uid' => 10, 'title' => 'Secret Group', 'type' => 'private_group']);
        DB::table('jcow_page_users')->insert(['pid' => 5, 'uid' => 30, 'role' => 1]);

        // 1. Private Stream -> hanya PrivateChannel('user.10'), TIDAK ada Channel('public-feed')
        $privateStream = new Stream(['uid' => 10, 'privacy' => 'private', 'app' => 'wall', 'hide' => 0]);
        $privateChannels = (new StreamCreated($privateStream))->broadcastOn();
        $channelNames = array_map(fn ($c) => $c->name, $privateChannels);
        $this->assertContains('private-user.10', $channelNames);
        $this->assertNotContains('public-feed', $channelNames);

        // 2. Friends Stream -> hanya teman & author di PrivateChannel, TIDAK ada Channel('public-feed')
        $friendsStream = new Stream(['uid' => 10, 'privacy' => 'friends', 'app' => 'wall', 'hide' => 0]);
        $friendsChannels = (new StreamCreated($friendsStream))->broadcastOn();
        $friendsChannelNames = array_map(fn ($c) => $c->name, $friendsChannels);
        $this->assertContains('private-user.10', $friendsChannelNames);
        $this->assertContains('private-user.20', $friendsChannelNames);
        $this->assertNotContains('public-feed', $friendsChannelNames);

        // 3. Private Group Stream -> hanya PrivateChannel('group.5') + member, TIDAK ada Channel('public-feed')
        $groupStream = new Stream(['uid' => 10, 'privacy' => 'public', 'app' => 'group', 'wall_id' => 5, 'aid' => 5, 'hide' => 0]);
        $groupChannels = (new StreamCreated($groupStream))->broadcastOn();
        $groupChannelNames = array_map(fn ($c) => $c->name, $groupChannels);
        $this->assertContains('private-group.5', $groupChannelNames);
        $this->assertContains('private-user.30', $groupChannelNames);
        $this->assertNotContains('public-feed', $groupChannelNames);

        // 4. Public Stream -> Channel('public-feed')
        $publicStream = new Stream(['uid' => 10, 'privacy' => 'public', 'app' => 'wall', 'hide' => 0]);
        $publicChannels = (new StreamCreated($publicStream))->broadcastOn();
        $this->assertCount(1, $publicChannels);
        $this->assertInstanceOf(Channel::class, $publicChannels[0]);
        $this->assertSame('public-feed', $publicChannels[0]->name);
    }

    public function test_sanctum_ai_security_webhook_requires_auth_and_stores_structured_log(): void
    {
        $payload = [
            'timestamp'   => '2026-11-11T15:00:00+07:00',
            'attack_type' => 'SQL Injection (UNION-Based)',
            'source_ip'   => '185.220.101.42',
            'target'      => "GET /search?q=1' UNION SELECT 1,username,password--",
            'severity'    => 'critical',
            'ai_analysis' => 'Terdeteksi eksploitasi UNION SELECT oleh agen SOC CrewAI.',
        ];

        // Tanpa token Sanctum -> 401 Unauthorized
        $unauthResponse = $this->postJson('/api/ai-security-logs', $payload);
        $unauthResponse->assertStatus(401);

        // Dengan token Sanctum -> 201 Created & tersimpan di ai_security_logs
        $admin = User::create([
            'username' => 'adminsoc',
            'password' => 'secret',
            'fullname' => 'Admin SOC',
            'email'    => 'adminsoc@xcode.id',
            'roles'    => '3',
            'level'    => 1,
        ]);

        Sanctum::actingAs($admin, ['ai-security:write']);

        $authResponse = $this->postJson('/api/ai-security-logs', $payload);
        $authResponse->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('recorded_count', 1);

        $this->assertDatabaseHas('ai_security_logs', [
            'attack_type' => 'SQL Injection (UNION-Based)',
            'source_ip'   => '185.220.101.42',
            'severity'    => 'critical',
        ]);
    }

    public function test_blocked_ip_middleware_blocks_suspended_ip(): void
    {
        BlockedIp::create([
            'ip_address' => '203.0.113.99',
            'reason'     => 'Brute Force Attack detected by CrewAI',
        ]);

        BlockedIp::clearBlockedCache();
        $this->assertTrue(BlockedIp::isBlocked('203.0.113.99'));

        $response = $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.99'])->get('/login');
        $response->assertStatus(403);
    }
}
