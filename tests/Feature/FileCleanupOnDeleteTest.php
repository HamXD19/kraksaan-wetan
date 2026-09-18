<?php

namespace Tests\Feature;

use App\Models\AgendaKegiatan;
use App\Models\Berita;
use App\Models\Dokumen;
use App\Models\Galeri;
use App\Models\Lembaga;
use App\Models\Pengumuman;
use App\Models\PerangkatKelurahan;
use App\Models\ProfilKelurahan;
use App\Models\User;
use App\Services\FileStorageHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileCleanupOnDeleteTest extends TestCase
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

    public function test_file_storage_helper_extracts_paths_and_handles_external_urls(): void
    {
        $this->assertNull(FileStorageHelper::getRelativePublicPath(null));
        $this->assertNull(FileStorageHelper::getRelativePublicPath(''));
        $this->assertNull(FileStorageHelper::getRelativePublicPath('https://images.unsplash.com/photo-123456'));
        $this->assertNull(FileStorageHelper::getRelativePublicPath('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg'));

        $this->assertEquals('uploads/sample.jpg', FileStorageHelper::getRelativePublicPath('uploads/sample.jpg'));
        $this->assertEquals('uploads/sample.jpg', FileStorageHelper::getRelativePublicPath('/storage/uploads/sample.jpg'));
        $this->assertEquals('uploads/sample.jpg', FileStorageHelper::getRelativePublicPath('http://localhost:8000/storage/uploads/sample.jpg'));
        $this->assertEquals('uploads/sample.jpg', FileStorageHelper::getRelativePublicPath('https://kraksaanwetan.go.id/storage/uploads/sample.jpg'));
    }

    public function test_berita_deletes_image_from_storage_on_admin_delete_and_model_delete(): void
    {
        Storage::fake('public');
        $token = $this->getAdminToken();

        // 1. Simpan dummy image di disk public
        $filename = 'berita_test_'.uniqid().'.jpg';
        Storage::disk('public')->put('uploads/'.$filename, 'fake image content');
        $this->assertTrue(Storage::disk('public')->exists('uploads/'.$filename));

        $berita = Berita::create([
            'slug' => 'test-berita-'.uniqid(),
            'judul' => 'Judul Berita Uji Hapus File',
            'kategori' => 'Pemerintahan',
            'tanggal' => '18 September 2026',
            'ringkasan' => 'Ringkasan berita uji coba',
            'konten' => '<p>Konten berita uji coba</p>',
            'gambar' => 'http://127.0.0.1:8000/storage/uploads/'.$filename,
        ]);

        // Hapus lewat endpoint admin
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/berita/'.$berita->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('beritas', ['id' => $berita->id]);
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$filename), 'File berita harus terhapus dari storage saat dihapus');
    }

    public function test_pengumuman_deletes_file_banner_and_thumbnail_from_storage(): void
    {
        Storage::fake('public');
        $token = $this->getAdminToken();

        $pdfName = 'pengumuman_'.uniqid().'.pdf';
        $bannerName = 'banner_'.uniqid().'.jpg';
        $thumbName = 'thumb_'.uniqid().'.png';

        Storage::disk('public')->put('uploads/'.$pdfName, 'dummy pdf');
        Storage::disk('public')->put('uploads/'.$bannerName, 'dummy banner');
        Storage::disk('public')->put('uploads/'.$thumbName, 'dummy thumb');

        $pengumuman = Pengumuman::create([
            'judul' => 'Pengumuman Uji Hapus Media',
            'tanggal' => '18 September 2026',
            'prioritas' => 'Penting',
            'isi' => '<p>Isi pengumuman</p>',
            'file' => 'uploads/'.$pdfName,
            'banner' => '/storage/uploads/'.$bannerName,
            'thumbnail' => 'http://localhost/storage/uploads/'.$thumbName,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/pengumuman/'.$pengumuman->id);

        $response->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$pdfName));
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$bannerName));
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$thumbName));
    }

    public function test_dokumen_deletes_file_from_storage(): void
    {
        Storage::fake('public');
        $token = $this->getAdminToken();

        $docName = 'dokumen_'.uniqid().'.pdf';
        Storage::disk('public')->put('uploads/'.$docName, 'dummy pdf content');

        $dokumen = Dokumen::create([
            'judul' => 'Dokumen Uji Hapus',
            'kategori' => 'Perencanaan',
            'periode' => 'Tahunan',
            'tahun' => '2026',
            'file' => 'uploads/'.$docName,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/dokumen/'.$dokumen->id);

        $response->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$docName));
    }

    public function test_galeri_deletes_image_from_storage(): void
    {
        Storage::fake('public');
        $token = $this->getAdminToken();

        $galeriName = 'galeri_'.uniqid().'.jpg';
        Storage::disk('public')->put('uploads/'.$galeriName, 'dummy image content');

        $galeri = Galeri::create([
            'judul' => 'Foto Kegiatan Uji Hapus',
            'kategori' => 'Sosial',
            'tipe' => 'foto',
            'tanggal' => '2026-09-18',
            'gambar' => 'uploads/'.$galeriName,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/galeri/'.$galeri->id);

        $response->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$galeriName));
    }

    public function test_agenda_deletes_poster_photo_from_storage(): void
    {
        Storage::fake('public');
        $token = $this->getAdminToken();

        $posterName = 'agenda_'.uniqid().'.jpg';
        Storage::disk('public')->put('uploads/'.$posterName, 'dummy poster content');

        $agenda = AgendaKegiatan::create([
            'judul' => 'Agenda Uji Hapus Poster '.uniqid(),
            'slug' => 'agenda-uji-hapus-'.uniqid(),
            'deskripsi' => '<p>Deskripsi agenda</p>',
            'tanggal_mulai' => now()->addDay(),
            'tanggal_selesai' => now()->addDays(2),
            'foto' => 'http://127.0.0.1:8000/storage/uploads/'.$posterName,
            'is_aktif' => true,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/agenda/'.$agenda->id);

        $response->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$posterName));
    }

    public function test_perangkat_deletes_photo_from_storage(): void
    {
        Storage::fake('public');
        $token = $this->getAdminToken();

        $photoName = 'perangkat_'.uniqid().'.png';
        Storage::disk('public')->put('uploads/'.$photoName, 'dummy photo content');

        $perangkat = PerangkatKelurahan::create([
            'nama' => 'Staf Uji Coba',
            'jabatan' => 'Kasi Pelayanan',
            'foto' => '/storage/uploads/'.$photoName,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/perangkat/'.$perangkat->id);

        $response->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$photoName));
    }

    public function test_lembaga_deletes_logo_from_storage(): void
    {
        Storage::fake('public');
        $token = $this->getAdminToken();

        $logoName = 'logo_'.uniqid().'.png';
        Storage::disk('public')->put('uploads/'.$logoName, 'dummy logo content');

        $lembaga = Lembaga::create([
            'nama' => 'Lembaga Uji Hapus '.uniqid(),
            'singkatan' => 'LUH',
            'deskripsi' => 'Deskripsi lembaga uji coba',
            'logo' => 'uploads/'.$logoName,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/lembaga/'.$lembaga->id);

        $response->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$logoName));
    }

    public function test_profil_cleans_up_old_image_on_update(): void
    {
        Storage::fake('public');
        $token = $this->getAdminToken();

        $oldHero = 'old_hero_'.uniqid().'.jpg';
        $newHero = 'new_hero_'.uniqid().'.jpg';

        Storage::disk('public')->put('uploads/'.$oldHero, 'old hero content');
        Storage::disk('public')->put('uploads/'.$newHero, 'new hero content');

        $profil = ProfilKelurahan::first();
        if (! $profil) {
            $profil = ProfilKelurahan::create([
                'nama' => 'Kelurahan Kraksaan Wetan',
                'alamat' => 'Jl. Mayjend Sutoyo No. 1',
                'deskripsi' => 'Deskripsi kelurahan',
                'visi' => 'Visi kelurahan',
                'hero_image' => 'uploads/'.$oldHero,
            ]);
        } else {
            $profil->update(['hero_image' => 'uploads/'.$oldHero]);
        }

        $this->assertTrue(Storage::disk('public')->exists('uploads/'.$oldHero));

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/admin/profil', [
                'nama' => $profil->nama,
                'alamat' => $profil->alamat,
                'deskripsi' => $profil->deskripsi,
                'visi' => $profil->visi,
                'hero_image' => 'uploads/'.$newHero,
            ]);

        $response->assertStatus(200);
        $this->assertFalse(Storage::disk('public')->exists('uploads/'.$oldHero), 'Berkas hero lama harus otomatis terhapus saat diganti');
        $this->assertTrue(Storage::disk('public')->exists('uploads/'.$newHero), 'Berkas hero baru tetap ada');
    }

    public function test_upload_endpoint_supports_video_files(): void
    {
        Storage::fake('public');
        $token = $this->getAdminToken();

        $videoFile = UploadedFile::fake()->create('dokumentasi_kegiatan.mp4', 5000, 'video/mp4');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/upload', [
                'file' => $videoFile,
                'type' => 'video',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $savedPath = $response->json('data.path');
        $this->assertNotNull($savedPath);
        $this->assertTrue(Storage::disk('public')->exists($savedPath));
    }
}
