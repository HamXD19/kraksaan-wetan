<?php

namespace Tests\Feature;

use App\Models\Dokumen;
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
}
