<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserRoleAccessControlTest extends TestCase
{
    protected User $superAdmin;
    protected string $superAdminToken;

    protected User $staffKonten;
    protected string $staffKontenToken;

    protected User $staffPelayanan;
    protected string $staffPelayananToken;

    protected User $staffAdministrasi;
    protected string $staffAdministrasiToken;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Super Admin
        $this->superAdmin = User::firstOrCreate(
            ['email' => 'admin_test_sa@kraksaanwetan.go.id'],
            [
                'name' => 'Super Admin Test',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_SUPER_ADMIN,
            ]
        );
        $this->superAdminToken = $this->createTokenFor($this->superAdmin);

        // 2. Staff Konten
        $this->staffKonten = User::firstOrCreate(
            ['email' => 'staff_konten_test@kraksaanwetan.go.id'],
            [
                'name' => 'Staff Konten Test',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_STAFF_KONTEN,
            ]
        );
        $this->staffKontenToken = $this->createTokenFor($this->staffKonten);

        // 3. Staff Pelayanan
        $this->staffPelayanan = User::firstOrCreate(
            ['email' => 'staff_pelayanan_test@kraksaanwetan.go.id'],
            [
                'name' => 'Staff Pelayanan Test',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_STAFF_PELAYANAN,
            ]
        );
        $this->staffPelayananToken = $this->createTokenFor($this->staffPelayanan);

        // 4. Staff Administrasi
        $this->staffAdministrasi = User::firstOrCreate(
            ['email' => 'staff_administrasi_test@kraksaanwetan.go.id'],
            [
                'name' => 'Staff Administrasi Test',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_STAFF_ADMINISTRASI,
            ]
        );
        $this->staffAdministrasiToken = $this->createTokenFor($this->staffAdministrasi);
    }

    private function createTokenFor(User $user): string
    {
        $token = base64_encode($user->id . '|' . Str::random(32) . '|' . time());
        Cache::put('admin_auth_token_' . $token, $user->id, now()->addDays(7));
        return $token;
    }

    public function test_login_returns_user_role_and_role_label(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => $this->staffKonten->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.user.role', User::ROLE_STAFF_KONTEN)
            ->assertJsonPath('data.user.role_label', 'Staff Konten & Humas');
    }

    public function test_super_admin_can_manage_staff_accounts(): void
    {
        // 1. Get staff list
        $resList = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->getJson('/api/admin/staff');

        $resList->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => ['counts', 'staff', 'roles'],
            ]);

        // 2. Create new staff
        $resCreate = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->postJson('/api/admin/staff', [
                'name' => 'Staf Baru Test',
                'email' => 'staf_baru@kraksaanwetan.go.id',
                'role' => User::ROLE_STAFF_PELAYANAN,
                'password' => 'password123',
            ]);

        $resCreate->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.role', User::ROLE_STAFF_PELAYANAN);

        $newStaffId = $resCreate->json('data.id');

        // 3. Update staff
        $resUpdate = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->putJson("/api/admin/staff/{$newStaffId}", [
                'name' => 'Staf Baru Diedit',
                'email' => 'staf_baru@kraksaanwetan.go.id',
                'role' => User::ROLE_STAFF_ADMINISTRASI,
            ]);

        $resUpdate->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.name', 'Staf Baru Diedit')
            ->assertJsonPath('data.role', User::ROLE_STAFF_ADMINISTRASI);

        // 4. Reset staff password
        $resPwd = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->putJson("/api/admin/staff/{$newStaffId}/reset-password", [
                'password' => 'newpassword123',
            ]);

        $resPwd->assertStatus(200)
            ->assertJsonPath('status', 'success');

        // 5. Delete staff
        $resDel = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->deleteJson("/api/admin/staff/{$newStaffId}");

        $resDel->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('users', ['id' => $newStaffId]);
    }

    public function test_super_admin_cannot_delete_self(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->superAdminToken)
            ->deleteJson("/api/admin/staff/{$this->superAdmin->id}");

        $response->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    public function test_staff_konten_permissions(): void
    {
        // Allowed: Berita, Pengumuman, Galeri
        $this->withHeader('Authorization', 'Bearer ' . $this->staffKontenToken)
            ->getJson('/api/admin/berita')
            ->assertStatus(200);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffKontenToken)
            ->getJson('/api/admin/pengumuman')
            ->assertStatus(200);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffKontenToken)
            ->getJson('/api/admin/galeri')
            ->assertStatus(200);

        // Forbidden: Staff, Layanan, Lembaga, Profil
        $this->withHeader('Authorization', 'Bearer ' . $this->staffKontenToken)
            ->getJson('/api/admin/staff')
            ->assertStatus(403);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffKontenToken)
            ->getJson('/api/admin/layanan')
            ->assertStatus(403);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffKontenToken)
            ->getJson('/api/admin/lembaga')
            ->assertStatus(403);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffKontenToken)
            ->putJson('/api/admin/profil', ['lurah_nama' => 'Test'])
            ->assertStatus(403);
    }

    public function test_staff_pelayanan_permissions(): void
    {
        // Allowed: Layanan
        $this->withHeader('Authorization', 'Bearer ' . $this->staffPelayananToken)
            ->getJson('/api/admin/layanan')
            ->assertStatus(200);

        // Forbidden: Berita, Galeri, Lembaga, Staff
        $this->withHeader('Authorization', 'Bearer ' . $this->staffPelayananToken)
            ->getJson('/api/admin/berita')
            ->assertStatus(403);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffPelayananToken)
            ->getJson('/api/admin/galeri')
            ->assertStatus(403);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffPelayananToken)
            ->getJson('/api/admin/lembaga')
            ->assertStatus(403);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffPelayananToken)
            ->getJson('/api/admin/staff')
            ->assertStatus(403);
    }

    public function test_staff_administrasi_permissions(): void
    {
        // Allowed: Lembaga, Transparansi
        $this->withHeader('Authorization', 'Bearer ' . $this->staffAdministrasiToken)
            ->getJson('/api/admin/lembaga')
            ->assertStatus(200);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffAdministrasiToken)
            ->getJson('/api/admin/transparansi')
            ->assertStatus(200);

        // Forbidden: Berita, Layanan, Staff
        $this->withHeader('Authorization', 'Bearer ' . $this->staffAdministrasiToken)
            ->getJson('/api/admin/berita')
            ->assertStatus(403);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffAdministrasiToken)
            ->getJson('/api/admin/layanan')
            ->assertStatus(403);

        $this->withHeader('Authorization', 'Bearer ' . $this->staffAdministrasiToken)
            ->getJson('/api/admin/staff')
            ->assertStatus(403);
    }

    public function test_all_staff_can_access_dashboard_and_me(): void
    {
        $tokens = [
            $this->superAdminToken,
            $this->staffKontenToken,
            $this->staffPelayananToken,
            $this->staffAdministrasiToken,
        ];

        foreach ($tokens as $token) {
            $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson('/api/admin/dashboard')
                ->assertStatus(200);

            $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson('/api/admin/me')
                ->assertStatus(200);
        }
    }

    protected function tearDown(): void
    {
        User::whereIn('email', [
            'admin_test_sa@kraksaanwetan.go.id',
            'staff_konten_test@kraksaanwetan.go.id',
            'staff_pelayanan_test@kraksaanwetan.go.id',
            'staff_administrasi_test@kraksaanwetan.go.id',
            'staf_baru@kraksaanwetan.go.id',
        ])->delete();

        parent::tearDown();
    }
}
