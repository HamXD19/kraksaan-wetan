<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
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

    public function test_public_can_submit_service_request_with_documents(): void
    {
        $ktpFile = UploadedFile::fake()->create('ktp_pemohon.jpg', 200, 'image/jpeg');
        $kkFile = UploadedFile::fake()->create('kartu_keluarga.pdf', 300, 'application/pdf');

        $payload = [
            'layanan_id' => $this->layanan->id,
            'nama_pemohon' => 'Ahmad Fauzi',
            'nik' => '3513012345678901',
            'no_kk' => '3513012345678902',
            'no_hp' => '081234567890',
            'alamat' => 'RT 01 / RW 02, Kelurahan Kraksaan Wetan',
            'keperluan' => 'Pendaftaran Pekerjaan',
            'keterangan' => 'Mohon diproses segera',
            'nama_dokumen' => ['KTP Pemohon', 'Kartu Keluarga'],
            'dokumen' => [$ktpFile, $kkFile],
        ];

        $response = $this->postJson('/api/pelayanan/pengajuan', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'nomor_pengajuan',
                    'nama_pemohon',
                    'layanan',
                    'status',
                    'tanggal_pengajuan',
                ],
            ]);

        $nomorPengajuan = $response->json('data.nomor_pengajuan');
        $this->assertStringStartsWith('KW-', $nomorPengajuan);

        // Verifikasi database
        $this->assertDatabaseHas('service_requests', [
            'nomor_pengajuan' => $nomorPengajuan,
            'nama_pemohon' => 'Ahmad Fauzi',
            'nik' => '3513012345678901',
            'status' => 'Menunggu Verifikasi',
        ]);

        // Verifikasi berkas tersimpan di tabel service_documents
        $serviceRequest = ServiceRequest::where('nomor_pengajuan', $nomorPengajuan)->first();
        $this->assertNotNull($serviceRequest);
        $this->assertCount(2, $serviceRequest->documents);
    }

    public function test_submission_fails_with_invalid_nik(): void
    {
        $payload = [
            'layanan_id' => $this->layanan->id,
            'nama_pemohon' => 'Ahmad Fauzi',
            'nik' => '123', // kurang dari 16 digit
            'no_hp' => '081234567890',
            'alamat' => 'Kraksaan',
            'keperluan' => 'Pekerjaan',
        ];

        $response = $this->postJson('/api/pelayanan/pengajuan', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }

    public function test_citizen_can_track_request_with_correct_nik(): void
    {
        $nomorPengajuan = ServiceRequest::generateNomorPengajuan();
        $request = ServiceRequest::create([
            'nomor_pengajuan' => $nomorPengajuan,
            'layanan_id' => $this->layanan->id,
            'nama_pemohon' => 'Siti Nurhaliza',
            'nik' => '3513098765432109',
            'no_kk' => '3513098765432100',
            'no_hp' => '089876543210',
            'alamat' => 'Lingkungan Kraksaan',
            'keperluan' => 'Surat Pengantar Beasiswa',
            'status' => 'Menunggu Verifikasi',
            'submitted_at' => now(),
        ]);

        $response = $this->postJson('/api/pelayanan/tracking', [
            'nomor_pengajuan' => $nomorPengajuan,
            'nik' => '3513098765432109',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'nomor_pengajuan' => $nomorPengajuan,
                    'nama_pemohon' => 'Siti Nurhaliza',
                    'masked_nik' => '351309******2109',
                    'status' => 'Menunggu Verifikasi',
                ],
            ]);
    }

    public function test_tracking_fails_with_wrong_nik(): void
    {
        $nomorPengajuan = ServiceRequest::generateNomorPengajuan();
        ServiceRequest::create([
            'nomor_pengajuan' => $nomorPengajuan,
            'layanan_id' => $this->layanan->id,
            'nama_pemohon' => 'Siti Nurhaliza',
            'nik' => '3513098765432109',
            'no_hp' => '089876543210',
            'alamat' => 'Lingkungan Kraksaan',
            'keperluan' => 'Surat Pengantar Beasiswa',
            'status' => 'Menunggu Verifikasi',
            'submitted_at' => now(),
        ]);

        $response = $this->postJson('/api/pelayanan/tracking', [
            'nomor_pengajuan' => $nomorPengajuan,
            'nik' => '3513098765439999', // salah
        ]);

        $response->assertStatus(404)
            ->assertJson(['status' => 'error']);
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
