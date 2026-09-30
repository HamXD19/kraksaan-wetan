<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DokumenClassificationTest extends TestCase
{
    private function getOrCreateAdmin(): User
    {
        $admin = User::where('email', 'admin@kraksaanwetan.go.id')->first();
        if (! $admin) {
            $admin = User::create([
                'name' => 'Administrator',
                'email' => 'admin@kraksaanwetan.go.id',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]);
        }

        return $admin;
    }

    private function getAdminToken(): string
    {
        $admin = $this->getOrCreateAdmin();
        $login = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        return $login->json('data.token');
    }

    public function test_public_dokumen_api_returns_classification_metadata(): void
    {
        $response = $this->getJson('/api/dokumen');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data',
                'meta' => [
                    'total',
                    'kategori_list',
                    'tahun_list',
                    'master_kategori_tree',
                ],
            ]);
    }

    public function test_master_kategori_hierarchy_and_subkategori_tree(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/kategori?modul=dokumen');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $data = $response->json('data');
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        // At least one category should have subkategoris
        $hasSubkategori = false;
        foreach ($data as $cat) {
            if (! empty($cat['subkategoris'])) {
                $hasSubkategori = true;
                break;
            }
        }
        $this->assertTrue($hasSubkategori, 'Expected at least one category with subkategoris');
    }

    public function test_admin_dokumen_filters_work_correctly(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/dokumen?periode=Tahunan');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data',
                'summary' => [
                    'total',
                    'aktif',
                    'total_unduhan',
                ],
                'meta' => [
                    'kategori_list',
                    'subkategori_list',
                    'tahun_list',
                    'master_kategori_tree',
                ],
            ]);
    }

    public function test_dokumen_label_periode_lengkap_accessor(): void
    {
        $doc = new Dokumen([
            'judul' => 'Uji Coba LAKIP',
            'periode' => '5 Tahunan',
            'tahun' => '2025',
            'tahun_selesai' => '2029',
        ]);

        $this->assertEquals('Periode 2025–2029', $doc->label_periode_lengkap);

        $docSemester = new Dokumen([
            'judul' => 'Laporan Semesteran',
            'periode' => 'Semesteran',
            'periode_ke' => 'Semester I',
            'tahun' => '2026',
        ]);

        $this->assertEquals('2026 — Semester I', $docSemester->label_periode_lengkap);
    }

    public function test_unduh_dokumen_returns_downloadable_pdf(): void
    {
        $doc = Dokumen::firstOrCreate(
            ['nomor_dokumen' => 'TEST/01/DOK/2026'],
            [
                'judul' => 'Uji Coba Unduh Dokumen Resmi',
                'nomor_dokumen' => 'TEST/01/DOK/2026',
                'kategori' => 'Pelayanan Publik',
                'periode' => 'Tahunan',
                'tahun' => '2026',
                'deskripsi' => 'Deskripsi berkas uji coba.',
                'file' => 'uploads/test_sample_dokumen.pdf',
                'aktif' => true,
            ]
        );

        $response = $this->get("/api/dokumen/{$doc->id}/unduh");

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type') ?? '');
    }

    public function test_pratinjau_dokumen_returns_inline_pdf(): void
    {
        $doc = Dokumen::first();
        $this->assertNotNull($doc);

        $response = $this->get("/api/dokumen/{$doc->id}/pratinjau");

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type') ?? '');
    }

    public function test_unduh_pengumuman_returns_downloadable_pdf(): void
    {
        $pengumuman = Pengumuman::firstOrCreate(
            ['judul' => 'Uji Coba Pengumuman Download'],
            [
                'judul' => 'Uji Coba Pengumuman Download',
                'tanggal' => '22 September 2026',
                'prioritas' => 'Penting',
                'isi' => 'Konten pengumuman pengujian berkas unduhan.',
                'file' => 'test_pengumuman.pdf',
            ]
        );

        $response = $this->get("/api/pengumuman/{$pengumuman->id}/unduh");

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type') ?? '');
    }

    public function test_admin_dokumen_returns_kategori_counts_and_filters_by_kategori(): void
    {
        $token = $this->getAdminToken();

        // Create sample dokumen for Musrenbang and UMKM
        Dokumen::create([
            'judul' => 'Dokumen Uji Musrenbang',
            'kategori' => 'Musrenbang',
            'periode' => 'Tahunan',
            'tahun' => '2026',
            'file' => 'uploads/sample_musrenbang.pdf',
            'aktif' => true,
        ]);

        Dokumen::create([
            'judul' => 'Dokumen Uji UMKM',
            'kategori' => 'UMKM',
            'periode' => 'Tahunan',
            'tahun' => '2026',
            'file' => 'uploads/sample_umkm.pdf',
            'aktif' => true,
        ]);

        // Get all documents
        $responseAll = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/dokumen');

        $responseAll->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'summary' => [
                    'total',
                    'aktif',
                    'total_unduhan',
                    'kategori_counts',
                ],
            ]);

        $kategoriCounts = $responseAll->json('summary.kategori_counts');
        $this->assertArrayHasKey('Musrenbang', $kategoriCounts);
        $this->assertArrayHasKey('UMKM', $kategoriCounts);
        $this->assertGreaterThanOrEqual(1, $kategoriCounts['Musrenbang']);
        $this->assertGreaterThanOrEqual(1, $kategoriCounts['UMKM']);

        // Filter specifically by Musrenbang
        $responseMusrenbang = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/dokumen?kategori=Musrenbang');

        $responseMusrenbang->assertStatus(200);
        $items = $responseMusrenbang->json('data');
        $this->assertNotEmpty($items);
        foreach ($items as $item) {
            $this->assertEquals('Musrenbang', $item['kategori']);
        }
    }
}
