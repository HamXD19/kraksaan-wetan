<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    protected User $superAdmin;

    protected string $superAdminToken;

    protected User $staffKonten;

    protected string $staffKontenToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::firstOrCreate(
            ['email' => 'sa_activity_test@kraksaanwetan.go.id'],
            [
                'name' => 'Super Admin Activity Test',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_SUPER_ADMIN,
            ]
        );
        $this->superAdminToken = $this->createTokenFor($this->superAdmin);

        $this->staffKonten = User::firstOrCreate(
            ['email' => 'sk_activity_test@kraksaanwetan.go.id'],
            [
                'name' => 'Staff Konten Activity Test',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_STAFF_KONTEN,
            ]
        );
        $this->staffKontenToken = $this->createTokenFor($this->staffKonten);
    }

    private function createTokenFor(User $user): string
    {
        $token = base64_encode($user->id.'|'.Str::random(32).'|'.time());
        Cache::put('admin_auth_token_'.$token, $user->id, now()->addDays(7));

        return $token;
    }

    public function test_login_records_activity_log(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => $this->staffKonten->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->staffKonten->id,
            'action' => 'login',
            'module' => 'auth',
        ]);
    }

    public function test_super_admin_can_access_activity_logs(): void
    {
        ActivityLog::record(
            action: 'create',
            module: 'berita',
            description: 'Test penerbitan berita',
            properties: ['judul' => 'Berita Percobaan'],
            user: $this->staffKonten
        );

        $response = $this->withHeader('Authorization', "Bearer {$this->superAdminToken}")
            ->getJson('/api/admin/activity-logs');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    'data',
                    'current_page',
                    'total',
                ],
                'summary' => [
                    'total_logs',
                    'today_logs',
                    'active_users_today',
                    'most_active_module',
                ],
            ]);
    }

    public function test_staff_cannot_access_activity_logs(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->staffKontenToken}")
            ->getJson('/api/admin/activity-logs');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_activity_logs(): void
    {
        $response = $this->getJson('/api/admin/activity-logs');

        $response->assertStatus(401);
    }

    public function test_activity_logs_filter_by_user_id(): void
    {
        ActivityLog::record(
            action: 'create',
            module: 'berita',
            description: 'Berita oleh staff konten',
            user: $this->staffKonten
        );

        ActivityLog::record(
            action: 'create',
            module: 'staff',
            description: 'Staf dibuat oleh super admin',
            user: $this->superAdmin
        );

        $response = $this->withHeader('Authorization', "Bearer {$this->superAdminToken}")
            ->getJson('/api/admin/activity-logs?user_id='.$this->staffKonten->id);

        $response->assertStatus(200);
        $data = $response->json('data.data');

        $this->assertNotEmpty($data);
        foreach ($data as $log) {
            $this->assertEquals($this->staffKonten->id, $log['user_id']);
        }
    }

    public function test_activity_logs_filter_by_module_and_action(): void
    {
        ActivityLog::record(
            action: 'create',
            module: 'pengumuman',
            description: 'Pengumuman baru',
            user: $this->staffKonten
        );

        ActivityLog::record(
            action: 'delete',
            module: 'pengumuman',
            description: 'Pengumuman dihapus',
            user: $this->staffKonten
        );

        $response = $this->withHeader('Authorization', "Bearer {$this->superAdminToken}")
            ->getJson('/api/admin/activity-logs?module=pengumuman&action=delete');

        $response->assertStatus(200);
        $data = $response->json('data.data');

        $this->assertNotEmpty($data);
        foreach ($data as $log) {
            $this->assertEquals('pengumuman', $log['module']);
            $this->assertEquals('delete', $log['action']);
        }
    }

    public function test_super_admin_can_get_activity_log_users(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->superAdminToken}")
            ->getJson('/api/admin/activity-logs/users');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'name', 'email', 'role'],
                ],
            ]);
    }
}
