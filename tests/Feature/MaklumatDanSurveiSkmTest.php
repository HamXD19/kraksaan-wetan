<?php

namespace Tests\Feature;

use App\Models\MaklumatPelayanan;
use App\Models\SurveiSkm;
use App\Models\User;
use App\Services\FileStorageHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MaklumatDanSurveiSkmTest extends TestCase
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

    public function test_public_can_get_active_maklumat_pelayanan(): void
    {
        MaklumatPelayanan::truncate();
        MaklumatPelayanan::create([
            'judul' => 'Maklumat Pelayanan Uji Coba',
            'nomor_sk' => 'SK.001/TEST/2026',
            'motto' => 'Melayani dengan Cepat',
            'konten' => 'Kami berjanji melayani sepenuh hati.',
            'gambar' => null,
            'aktif' => true,
        ]);

        $response = $this->getJson('/api/maklumat-pelayanan');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.judul', 'Maklumat Pelayanan Uji Coba');

        $v1Response = $this->getJson('/api/v1/maklumat-pelayanan');
        $v1Response->assertStatus(200)
            ->assertJsonPath('data.judul', 'Maklumat Pelayanan Uji Coba');
    }

    public function test_public_can_get_active_survei_skm_list_and_detail(): void
    {
        SurveiSkm::truncate();
        $skm = SurveiSkm::create([
            'tahun' => '2026',
            'periode' => 'Semester I 2026',
            'skor_ikm' => 89.25,
            'skala_maksimal' => 100.00,
            'mutu_pelayanan' => 'A',
            'predikat' => 'Sangat Baik',
            'jumlah_responden' => 120,
            'metodologi' => 'PermenPAN-RB No. 14 Tahun 2017',
            'unsur_penilaian' => [
                ['nama' => '1. Persyaratan', 'nilai' => 90.0],
                ['nama' => '2. Prosedur', 'nilai' => 88.5],
            ],
            'aktif' => true,
            'urutan' => 1,
        ]);

        $response = $this->getJson('/api/survei-skm');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.skm_terbaru.periode', 'Semester I 2026')
            ->assertJsonPath('data.skm_terbaru.skor_ikm', 89.25);

        $detailResponse = $this->getJson("/api/survei-skm/{$skm->id}");
        $detailResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.id', $skm->id)
            ->assertJsonPath('data.unsur_penilaian.0.nama', '1. Persyaratan');
    }

    public function test_admin_can_manage_maklumat_pelayanan(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/maklumat-pelayanan', [
                'judul' => 'Maklumat Pelayanan Terkini 2026',
                'nomor_sk' => 'SK.050/2026',
                'motto' => 'Integritas & Profesionalitas',
                'konten' => 'Komitmen pelayanan standar prima bagi warga.',
                'aktif' => true,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.judul', 'Maklumat Pelayanan Terkini 2026');

        $getAdmin = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/maklumat-pelayanan');

        $getAdmin->assertStatus(200)
            ->assertJsonPath('data.judul', 'Maklumat Pelayanan Terkini 2026');
    }

    public function test_newer_maklumat_automatically_deactivates_older_maklumat(): void
    {
        $token = $this->getAdminToken();

        // 1. Terbitkan maklumat pertama (versi 2025)
        $firstRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/maklumat-pelayanan', [
                'judul' => 'Maklumat Pelayanan 2025 (Versi Lama)',
                'nomor_sk' => 'SK.001/2025',
                'konten' => 'Maklumat versi lama 2025.',
                'aktif' => true,
            ]);
        $firstRes->assertStatus(200);
        $firstId = $firstRes->json('data.id');

        $this->assertDatabaseHas('maklumat_pelayanans', [
            'id' => $firstId,
            'aktif' => true,
        ]);

        // 2. Terbitkan maklumat kedua / terbaru (versi 2026) dengan is_new_version = true
        $secondRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/maklumat-pelayanan', [
                'is_new_version' => true,
                'judul' => 'Maklumat Pelayanan 2026 (Versi Terbaru)',
                'nomor_sk' => 'SK.002/2026',
                'konten' => 'Maklumat versi terbaru 2026.',
                'aktif' => true,
            ]);
        $secondRes->assertStatus(200);
        $secondId = $secondRes->json('data.id');

        // Pastikan maklumat lama (firstId) otomatis nonaktif (false)
        $this->assertDatabaseHas('maklumat_pelayanans', [
            'id' => $firstId,
            'aktif' => false,
        ]);

        // Pastikan maklumat baru (secondId) berstatus aktif (true)
        $this->assertDatabaseHas('maklumat_pelayanans', [
            'id' => $secondId,
            'aktif' => true,
        ]);

        // 3. Pastikan API publik mengembalikan maklumat terbaru yang aktif
        $pubRes = $this->getJson('/api/maklumat-pelayanan');
        $pubRes->assertStatus(200)
            ->assertJsonPath('data.id', $secondId)
            ->assertJsonPath('data.judul', 'Maklumat Pelayanan 2026 (Versi Terbaru)');

        // 4. Toggle kembali maklumat pertama menjadi aktif
        $toggleRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/admin/maklumat-pelayanan/{$firstId}/toggle-aktif");
        $toggleRes->assertStatus(200);

        // Sekarang maklumat pertama aktif, maklumat kedua otomatis nonaktif
        $this->assertDatabaseHas('maklumat_pelayanans', [
            'id' => $firstId,
            'aktif' => true,
        ]);
        $this->assertDatabaseHas('maklumat_pelayanans', [
            'id' => $secondId,
            'aktif' => false,
        ]);
    }

    public function test_admin_can_crud_dynamic_survei_skm(): void
    {
        $token = $this->getAdminToken();

        // 1. Create
        $createRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/survei-skm', [
                'tahun' => '2026',
                'periode' => 'Semester II 2026',
                'skor_ikm' => 91.50,
                'skala_maksimal' => 100.00,
                'mutu_pelayanan' => 'A',
                'predikat' => 'Sangat Baik',
                'jumlah_responden' => 210,
                'metodologi' => 'Sesuai Pedoman SKM Mandiri',
                'unsur_penilaian' => [
                    ['nama' => '1. Kecepatan Respon', 'nilai' => 92.0],
                    ['nama' => '2. Kepastian Syarat', 'nilai' => 91.0],
                ],
                'link_survei' => 'https://forms.gle/test-skm',
                'aktif' => true,
                'urutan' => 0,
            ]);

        $createRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $skmId = $createRes->json('data.id');
        $this->assertNotNull($skmId);

        // 2. Update
        $updateRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/admin/survei-skm/{$skmId}", [
                'tahun' => '2026',
                'periode' => 'Semester II 2026 (Revisi)',
                'skor_ikm' => 92.00,
                'skala_maksimal' => 100.00,
                'mutu_pelayanan' => 'A',
                'predikat' => 'Sangat Baik',
                'jumlah_responden' => 215,
                'metodologi' => 'Sesuai Pedoman SKM Mandiri Revisi',
                'unsur_penilaian' => [
                    ['nama' => '1. Kecepatan Respon', 'nilai' => 93.0],
                ],
                'aktif' => true,
            ]);

        $updateRes->assertStatus(200)
            ->assertJsonPath('data.periode', 'Semester II 2026 (Revisi)');
        $this->assertEquals(92.0, (float) $updateRes->json('data.skor_ikm'));

        // 3. Toggle Aktif
        $toggleRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/admin/survei-skm/{$skmId}/toggle-aktif", [
                'aktif' => false,
            ]);
        $toggleRes->assertStatus(200)
            ->assertJsonPath('data.aktif', false);

        // 4. Delete
        $deleteRes = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/admin/survei-skm/{$skmId}");
        $deleteRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('survei_skms', ['id' => $skmId]);
    }

    public function test_file_cleanup_on_survei_skm_and_maklumat_delete_or_update(): void
    {
        Storage::fake('public');
        $fakePdf = UploadedFile::fake()->create('laporan-skm-2026.pdf', 300, 'application/pdf');
        $pathPdf = $fakePdf->store('uploads', 'public');

        $fakeImg = UploadedFile::fake()->image('poster-maklumat.jpg');
        $pathImg = $fakeImg->store('uploads', 'public');

        $this->assertTrue(Storage::disk('public')->exists($pathPdf));
        $this->assertTrue(Storage::disk('public')->exists($pathImg));

        $skm = SurveiSkm::create([
            'tahun' => '2026',
            'periode' => 'Laporan Uji PDF',
            'skor_ikm' => 88.0,
            'skala_maksimal' => 100.0,
            'mutu_pelayanan' => 'A',
            'predikat' => 'Sangat Baik',
            'jumlah_responden' => 50,
            'file_laporan' => "/storage/{$pathPdf}",
            'aktif' => true,
        ]);

        $maklumat = MaklumatPelayanan::create([
            'judul' => 'Maklumat File Test',
            'konten' => 'Uji hapus gambar poster',
            'gambar' => "/storage/{$pathImg}",
            'aktif' => true,
        ]);

        // Verifikasi bahwa FileStorageHelper mencatat kedua file aktif
        $activeFiles = FileStorageHelper::getAllActiveDatabaseFiles();
        $this->assertContains($pathPdf, $activeFiles);
        $this->assertContains($pathImg, $activeFiles);

        // Hapus SKM -> file PDF harus terhapus
        $skm->delete();
        $this->assertFalse(Storage::disk('public')->exists($pathPdf));

        // Hapus Maklumat -> file Gambar harus terhapus
        $maklumat->delete();
        $this->assertFalse(Storage::disk('public')->exists($pathImg));
    }
}
