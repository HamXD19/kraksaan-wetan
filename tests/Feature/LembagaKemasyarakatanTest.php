<?php

namespace Tests\Feature;

use App\Models\Lembaga;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class LembagaKemasyarakatanTest extends TestCase
{
    protected User $admin;
    protected string $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::first() ?? User::create([
            'name' => 'Admin Test',
            'email' => 'admin_lembaga@kraksaanwetan.go.id',
            'password' => bcrypt('password123'),
        ]);

        $this->adminToken = base64_encode($this->admin->id . '|' . Str::random(32) . '|' . time());
        Cache::put('admin_auth_token_' . $this->adminToken, $this->admin->id, now()->addDays(7));
    }

    public function test_public_can_get_active_lembaga_list(): void
    {
        $response = $this->getJson('/api/lembaga');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'nama',
                        'singkatan',
                        'kategori',
                        'ketua',
                        'deskripsi',
                        'program_kerja',
                        'warna_tema',
                        'urutan',
                        'aktif',
                    ]
                ]
            ]);

        $this->assertGreaterThanOrEqual(5, count($response->json('data')));
    }

    public function test_public_cannot_see_inactive_lembaga(): void
    {
        $lembaga = Lembaga::first();
        $this->assertNotNull($lembaga);

        $lembaga->update(['aktif' => false]);

        $response = $this->getJson('/api/lembaga');
        $response->assertStatus(200);

        $ids = collect($response->json('data'))->pluck('id')->all();
        $this->assertNotContains($lembaga->id, $ids);

        // Restore
        $lembaga->update(['aktif' => true]);
    }

    public function test_unauthenticated_cannot_access_admin_lembaga_api(): void
    {
        $this->getJson('/api/admin/lembaga')->assertStatus(401);
        $this->postJson('/api/admin/lembaga', ['nama' => 'Test'])->assertStatus(401);
        $this->putJson('/api/admin/lembaga/1', ['nama' => 'Test'])->assertStatus(401);
        $this->deleteJson('/api/admin/lembaga/1')->assertStatus(401);
    }

    public function test_admin_can_get_all_lembaga(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->getJson('/api/admin/lembaga');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertGreaterThanOrEqual(5, count($response->json('data')));
    }

    public function test_admin_can_create_lembaga(): void
    {
        $payload = [
            'nama' => 'Forum Kerukunan Umat Beragama Kelurahan',
            'singkatan' => 'FKUB',
            'kategori' => 'Sosial & Keagamaan',
            'ketua' => 'K.H. Mansyur Hidayat',
            'kontak' => '081234567899',
            'alamat' => 'Sekretariat FKUB Kraksaan Wetan',
            'deskripsi' => 'Wadah dialog dan penguatan toleransi serta kerukunan antarumat beragama di Kraksaan Wetan.',
            'program_kerja' => [
                'Dialog lintas pemuda dan tokoh agama rutin tiap triwulan',
                'Bakti sosial bersama di tempat ibadah'
            ],
            'jumlah_anggota' => '15 Tokoh Masyarakat',
            'warna_tema' => 'emerald',
            'urutan' => 99,
            'aktif' => true,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->postJson('/api/admin/lembaga', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.singkatan', 'FKUB');

        $this->assertDatabaseHas('lembagas', [
            'singkatan' => 'FKUB',
            'ketua' => 'K.H. Mansyur Hidayat',
        ]);
    }

    public function test_admin_can_update_lembaga(): void
    {
        $lembaga = Lembaga::where('singkatan', 'LPMK')->first();
        $this->assertNotNull($lembaga);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->putJson("/api/admin/lembaga/{$lembaga->id}", [
                'nama' => $lembaga->nama,
                'singkatan' => 'LPMK',
                'kategori' => 'Pemberdayaan Masyarakat',
                'ketua' => 'H. Suwandi, S.E. (Pembaruan)',
                'kontak' => $lembaga->kontak,
                'alamat' => $lembaga->alamat,
                'deskripsi' => $lembaga->deskripsi,
                'program_kerja' => ['Program Musrenbang 2026'],
                'jumlah_anggota' => '25 Pengurus',
                'warna_tema' => 'amber',
                'urutan' => 1,
                'aktif' => true,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.ketua', 'H. Suwandi, S.E. (Pembaruan)');

        $this->assertDatabaseHas('lembagas', [
            'id' => $lembaga->id,
            'ketua' => 'H. Suwandi, S.E. (Pembaruan)',
        ]);
    }

    public function test_admin_can_toggle_lembaga_active_status(): void
    {
        $lembaga = Lembaga::where('singkatan', 'TP-PKK')->first();
        $this->assertNotNull($lembaga);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->putJson("/api/admin/lembaga/{$lembaga->id}/toggle-aktif", [
                'aktif' => false,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.aktif', false);

        $this->assertDatabaseHas('lembagas', [
            'id' => $lembaga->id,
            'aktif' => false,
        ]);

        // Restore
        $lembaga->update(['aktif' => true]);
    }

    public function test_admin_can_delete_lembaga(): void
    {
        $lembaga = Lembaga::create([
            'nama' => 'Lembaga Sementara Untuk Tes',
            'singkatan' => 'TEST_DEL',
            'deskripsi' => 'Hanya untuk pengujian hapus.',
            'aktif' => true,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->adminToken)
            ->deleteJson("/api/admin/lembaga/{$lembaga->id}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('lembagas', [
            'id' => $lembaga->id,
        ]);
    }

    protected function tearDown(): void
    {
        Lembaga::where('singkatan', 'FKUB')->delete();
        Lembaga::where('singkatan', 'TEST_DEL')->delete();
        Lembaga::query()->update(['aktif' => true]);
        Lembaga::where('singkatan', 'LPMK')->update(['ketua' => 'H. Suwandi, S.E.']);
        parent::tearDown();
    }
}
