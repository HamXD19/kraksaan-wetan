<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class MasterKategoriTest extends TestCase
{
    protected User $superAdmin;

    protected string $superAdminToken;

    protected User $staffAdministrasi;

    protected string $staffAdministrasiToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::firstOrCreate(
            ['email' => 'admin_test_sa@kraksaanwetan.go.id'],
            [
                'name' => 'Super Admin Test',
                'password' => bcrypt('password123'),
                'role' => User::ROLE_SUPER_ADMIN,
            ]
        );
        $this->superAdminToken = base64_encode($this->superAdmin->id.'|'.Str::random(32).'|'.time());
        Cache::put('admin_auth_token_'.$this->superAdminToken, $this->superAdmin->id, now()->addDays(7));

        $this->staffAdministrasi = User::firstOrCreate(
            ['email' => 'staff_administrasi_test@kraksaanwetan.go.id'],
            [
                'name' => 'Staff Administrasi Test',
                'password' => bcrypt('password123'),
                'role' => User::ROLE_STAFF_ADMINISTRASI,
            ]
        );
        $this->staffAdministrasiToken = base64_encode($this->staffAdministrasi->id.'|'.Str::random(32).'|'.time());
        Cache::put('admin_auth_token_'.$this->staffAdministrasiToken, $this->staffAdministrasi->id, now()->addDays(7));
    }

    public function test_public_can_get_kategori_list(): void
    {
        $response = $this->getJson('/api/kategori?modul=berita');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'modul', 'nama', 'slug', 'warna', 'urutan', 'aktif'],
                ],
            ]);
    }

    public function test_admin_can_crud_master_kategori(): void
    {
        // 1. Create category
        $createRes = $this->withHeader('Authorization', 'Bearer '.$this->superAdminToken)
            ->postJson('/api/admin/kategori', [
                'modul' => 'berita',
                'nama' => 'Teknologi & Inovasi',
                'warna' => 'blue',
                'urutan' => 99,
                'aktif' => true,
            ]);

        $createRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $createdId = $createRes->json('data.id');

        // 2. Update category
        $updateRes = $this->withHeader('Authorization', 'Bearer '.$this->superAdminToken)
            ->putJson('/api/admin/kategori/'.$createdId, [
                'modul' => 'berita',
                'nama' => 'Teknologi & Digital',
                'warna' => 'indigo',
                'urutan' => 98,
                'aktif' => true,
            ]);

        $updateRes->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.nama', 'Teknologi & Digital');

        // 3. Delete category
        $deleteRes = $this->withHeader('Authorization', 'Bearer '.$this->superAdminToken)
            ->deleteJson('/api/admin/kategori/'.$createdId);

        $deleteRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('master_kategoris', ['id' => $createdId]);
    }

    public function test_staff_administrasi_can_access_transparansi_anggaran(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->staffAdministrasiToken)
            ->getJson('/api/admin/transparansi');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }

    public function test_profil_returns_span_lapor_and_halo_sae(): void
    {
        $response = $this->getJson('/api/profil');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    'link_span_lapor',
                    'halo_sae_wa',
                    'halo_sae_link',
                ],
            ]);
    }
}
