<?php

namespace Tests\Feature;

use Tests\TestCase;

class KelurahanApiTest extends TestCase
{
    public function test_spa_root_and_routes_return_200(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/profil')->assertStatus(200);
        $this->get('/berita')->assertStatus(200);
        $this->get('/pelayanan')->assertStatus(200);
        $this->get('/kontak')->assertStatus(200);
    }

    public function test_profil_api_returns_success(): void
    {
        $response = $this->getJson('/api/profil');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.nama', 'Kelurahan Kraksaan Wetan')
            ->assertJsonPath('data.kecamatan', 'Kraksaan');
    }

    public function test_statistik_api_returns_success(): void
    {
        $response = $this->getJson('/api/statistik');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    'penduduk',
                    'kk',
                    'laki_laki',
                    'perempuan',
                    'rt',
                    'rw',
                    'luas_wilayah',
                    'lingkungan',
                ],
            ]);
    }

    public function test_layanan_api_returns_success(): void
    {
        $response = $this->getJson('/api/layanan');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(6, 'data');
    }

    public function test_berita_api_and_slug_returns_success(): void
    {
        $response = $this->getJson('/api/berita');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $slug = 'musrenbangkel-kraksaan-wetan-2026-sepakati-prioritas-drainase-dan-pemberdayaan-umkm';
        $detail = $this->getJson("/api/berita/{$slug}");
        $detail->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.slug', $slug);
    }

    public function test_pengumuman_api_returns_success(): void
    {
        $response = $this->getJson('/api/pengumuman');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }

    public function test_galeri_api_returns_success(): void
    {
        $response = $this->getJson('/api/galeri');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $first = $response->json('data.0');
        if ($first) {
            $detail = $this->getJson("/api/galeri/{$first['id']}");
            $detail->assertStatus(200)
                ->assertJsonPath('status', 'success')
                ->assertJsonPath('data.id', $first['id']);
        }

        $notFound = $this->getJson('/api/galeri/999999');
        $notFound->assertStatus(404)
            ->assertJsonPath('status', 'error');
    }

    public function test_kirim_kontak_api_with_validation(): void
    {
        $payload = [
            'nama' => 'Budi Santoso',
            'telepon' => '08123456789',
            'email' => 'budi@example.com',
            'kategori' => 'Pengaduan Pelayanan',
            'pesan' => 'Mohon informasi jadwal pelayanan perekaman KTP keliling.',
        ];

        $response = $this->postJson('/api/kontak', $payload);
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }
}
