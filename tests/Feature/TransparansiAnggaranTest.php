<?php

namespace Tests\Feature;

use App\Models\TransparansiAnggaran;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class TransparansiAnggaranTest extends TestCase
{
    protected User $admin;

    protected string $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::first() ?? User::create([
            'name' => 'Admin Test',
            'email' => 'admin_transparansi@kraksaanwetan.go.id',
            'password' => bcrypt('password123'),
        ]);

        $this->adminToken = base64_encode($this->admin->id.'|'.Str::random(32).'|'.time());
        Cache::put('admin_auth_token_'.$this->adminToken, $this->admin->id, now()->addDays(7));
    }

    public function test_public_can_get_transparansi_summary_and_list(): void
    {
        $response = $this->getJson('/api/transparansi');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    'summary' => [
                        'tahun_terpilih',
                        'total_rencana',
                        'total_realisasi',
                        'total_sisa',
                        'persentase_total',
                        'total_kegiatan',
                        'kegiatan_selesai',
                        'kegiatan_berjalan',
                        'daftar_tahun',
                        'daftar_kategori',
                        'breakdown_kategori' => [
                            '*' => [
                                'kategori',
                                'rencana',
                                'realisasi',
                                'sisa',
                                'persentase',
                                'jumlah_kegiatan',
                            ],
                        ],
                    ],
                    'kegiatan' => [
                        '*' => [
                            'id',
                            'tahun',
                            'program',
                            'kegiatan',
                            'kategori',
                            'sumber_dana',
                            'anggaran_rencana',
                            'anggaran_realisasi',
                            'sisa_anggaran',
                            'persentase_realisasi',
                            'penerima_manfaat_target',
                            'penerima_manfaat_realisasi',
                            'progres_fisik',
                            'status',
                            'aktif',
                        ],
                    ],
                ],
            ]);

        $this->assertGreaterThanOrEqual(1, count($response->json('data.kegiatan')));
    }

    public function test_public_can_filter_transparansi_by_year(): void
    {
        $response = $this->getJson('/api/transparansi?tahun=2026');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.summary.tahun_terpilih', 2026);

        foreach ($response->json('data.kegiatan') as $item) {
            $this->assertEquals(2026, $item['tahun']);
        }
    }

    public function test_public_cannot_see_inactive_transparansi(): void
    {
        $item = TransparansiAnggaran::first();
        $this->assertNotNull($item);

        $item->update(['aktif' => false]);

        $response = $this->getJson('/api/transparansi');
        $response->assertStatus(200);

        $ids = collect($response->json('data.kegiatan'))->pluck('id')->all();
        $this->assertNotContains($item->id, $ids);

        // Restore
        $item->update(['aktif' => true]);
    }

    public function test_unauthenticated_cannot_access_admin_transparansi_api(): void
    {
        $this->getJson('/api/admin/transparansi')->assertStatus(401);
        $this->postJson('/api/admin/transparansi', ['kegiatan' => 'Test'])->assertStatus(401);
        $this->putJson('/api/admin/transparansi/1', ['kegiatan' => 'Test'])->assertStatus(401);
        $this->deleteJson('/api/admin/transparansi/1')->assertStatus(401);
    }

    public function test_admin_can_get_all_transparansi(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->getJson('/api/admin/transparansi');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    'summary',
                    'items' => [
                        '*' => [
                            'id',
                            'tahun',
                            'program',
                            'kegiatan',
                            'anggaran_rencana',
                            'anggaran_realisasi',
                        ],
                    ],
                ],
            ]);

        $this->assertGreaterThanOrEqual(1, count($response->json('data.items')));
    }

    public function test_admin_can_create_transparansi(): void
    {
        $payload = [
            'tahun' => 2026,
            'program' => 'Program Pengujian Otomatis Sistem Transparansi',
            'kegiatan' => 'Kegiatan Uji Coba Input Realisasi Anggaran Baru',
            'kategori' => 'Infrastruktur Lingkungan',
            'sumber_dana' => 'ADK Kraksaan Wetan',
            'anggaran_rencana' => 50000000,
            'anggaran_realisasi' => 25000000,
            'penerima_manfaat_target' => '500 Warga RT 01',
            'penerima_manfaat_realisasi' => '250 Warga RT 01',
            'progres_fisik' => 50,
            'status' => 'Sedang Berjalan',
            'lokasi' => 'RW 01 Kraksaan Wetan',
            'penanggung_jawab' => 'Kasi Pembangunan & Pokmas',
            'deskripsi' => 'Pengujian entri anggaran rencana vs realisasi dengan kalkulasi dinamis.',
            'urutan' => 999,
            'aktif' => true,
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->postJson('/api/admin/transparansi', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.kegiatan', 'Kegiatan Uji Coba Input Realisasi Anggaran Baru')
            ->assertJsonPath('data.sisa_anggaran', 25000000);

        $this->assertEquals(50.0, (float) $response->json('data.persentase_realisasi'));

        $this->assertDatabaseHas('transparansi_anggarans', [
            'kegiatan' => 'Kegiatan Uji Coba Input Realisasi Anggaran Baru',
            'anggaran_rencana' => 50000000,
            'anggaran_realisasi' => 25000000,
        ]);
    }

    public function test_admin_can_update_transparansi(): void
    {
        $item = TransparansiAnggaran::where('kegiatan', 'like', '%Drainase%')->first()
            ?? TransparansiAnggaran::first();

        $this->assertNotNull($item);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson("/api/admin/transparansi/{$item->id}", [
                'tahun' => $item->tahun,
                'program' => $item->program,
                'kegiatan' => $item->kegiatan,
                'kategori' => $item->kategori,
                'sumber_dana' => $item->sumber_dana,
                'anggaran_rencana' => 75000000,
                'anggaran_realisasi' => 70000000,
                'penerima_manfaat_target' => $item->penerima_manfaat_target,
                'penerima_manfaat_realisasi' => 'Update Realisasi 320 KK',
                'progres_fisik' => 95,
                'status' => 'Sedang Berjalan',
                'urutan' => 1,
                'aktif' => true,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.anggaran_realisasi', 70000000)
            ->assertJsonPath('data.sisa_anggaran', 5000000);

        $this->assertDatabaseHas('transparansi_anggarans', [
            'id' => $item->id,
            'anggaran_realisasi' => 70000000,
            'progres_fisik' => 95,
        ]);
    }

    public function test_admin_can_toggle_transparansi_active(): void
    {
        $item = TransparansiAnggaran::first();
        $this->assertNotNull($item);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson("/api/admin/transparansi/{$item->id}/toggle-aktif", [
                'aktif' => false,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.aktif', false);

        $this->assertDatabaseHas('transparansi_anggarans', [
            'id' => $item->id,
            'aktif' => false,
        ]);

        // Restore
        $item->update(['aktif' => true]);
    }

    public function test_admin_can_delete_transparansi(): void
    {
        $temp = TransparansiAnggaran::create([
            'tahun' => 2026,
            'program' => 'Program Untuk Dihapus',
            'kegiatan' => 'Kegiatan Temp Hapus',
            'kategori' => 'Lainnya',
            'sumber_dana' => 'Lainnya',
            'anggaran_rencana' => 1000000,
            'anggaran_realisasi' => 0,
            'aktif' => true,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->deleteJson("/api/admin/transparansi/{$temp->id}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('transparansi_anggarans', [
            'id' => $temp->id,
        ]);
    }

    public function test_transparansi_validation_rejects_negative_budget(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->postJson('/api/admin/transparansi', [
                'tahun' => 2026,
                'program' => 'Test Validasi',
                'kegiatan' => 'Test Validasi Negatif',
                'kategori' => 'Infrastruktur Lingkungan',
                'sumber_dana' => 'ADK',
                'anggaran_rencana' => -1000,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['anggaran_rencana']);
    }

    protected function tearDown(): void
    {
        TransparansiAnggaran::where('kegiatan', 'Kegiatan Uji Coba Input Realisasi Anggaran Baru')->delete();
        TransparansiAnggaran::where('kegiatan', 'Kegiatan Temp Hapus')->delete();
        TransparansiAnggaran::query()->update(['aktif' => true]);
        parent::tearDown();
    }
}
