<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class PelayananOnlineTest extends TestCase
{
    protected User $admin;

    protected string $adminToken;

    protected Layanan $layanan;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->admin = User::first() ?? User::create([
            'name' => 'Admin Test',
            'email' => 'admin_test@kraksaanwetan.go.id',
            'password' => bcrypt('password123'),
        ]);

        $this->adminToken = base64_encode($this->admin->id.'|'.Str::random(32).'|'.time());
        Cache::put('admin_auth_token_'.$this->adminToken, $this->admin->id, now()->addDays(7));

        $this->layanan = Layanan::first();
        if (! $this->layanan) {
            $this->layanan = Layanan::create([
                'judul' => 'Surat Pengantar KTP',
                'slug' => 'surat-pengantar-ktp',
                'kategori' => 'Kependudukan',
                'deskripsi' => 'Pengurusan surat pengantar KTP',
                'persyaratan' => ['KTP Lama', 'Kartu Keluarga'],
                'alur' => 'Pengajuan - Verifikasi - Selesai',
                'waktu' => '1 Hari Kerja',
                'biaya' => 'Gratis',
                'icon' => 'Users',
                'urutan' => 1,
                'aktif' => true,
            ]);
        }
        $this->layanan->update(['aktif' => true]);
    }

    public function test_public_can_get_active_services(): void
    {
        $response = $this->getJson('/api/pelayanan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'judul', 'slug', 'kategori', 'biaya', 'waktu', 'aktif'],
                ],
            ]);
    }

    public function test_admin_can_toggle_service_active_state(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson("/api/admin/layanan/{$this->layanan->id}/toggle-aktif", [
                'aktif' => false,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $this->layanan->id,
                    'aktif' => false,
                ],
            ]);

        $this->assertDatabaseHas('layanans', [
            'id' => $this->layanan->id,
            'aktif' => 0,
        ]);

        $this->layanan->update(['aktif' => true]);
    }

    protected function tearDown(): void
    {
        Layanan::query()->update(['aktif' => true]);
        parent::tearDown();
    }
}
