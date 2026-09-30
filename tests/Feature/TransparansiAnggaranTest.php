<?php

namespace Tests\Feature;

use App\Models\AnggaranRealisasi;
use App\Models\AnggaranRealisasiItem;
use App\Models\TransparansiAnggaran;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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

    public function test_admin_can_bulk_create_apbd_in_single_transaction(): void
    {
        $payload = [
            'judul' => 'Anggaran Pendapatan dan Belanja Kelurahan Kraksaan Wetan Tahun Anggaran 2030',
            'tahun' => 2030,
            'tanggal_publikasi' => '2030-01-15',
            'status' => 'published',
            'deskripsi' => 'Pengujian bulk single form APBD dengan seluruh pos pendapatan, belanja, dan pembiayaan.',
            'items' => [
                [
                    'tipe' => 'pendapatan',
                    'kategori' => 'Pendapatan Asli',
                    'uraian' => 'Hasil Pengelolaan Aset Kelurahan',
                    'anggaran' => 50000000,
                    'realisasi' => 50000000,
                ],
                [
                    'tipe' => 'pendapatan',
                    'kategori' => 'Pendapatan Transfer',
                    'uraian' => 'Alokasi Dana Kelurahan (ADK)',
                    'anggaran' => 400000000,
                    'realisasi' => 380000000,
                ],
                [
                    'tipe' => 'belanja',
                    'kategori' => 'Penyelenggaraan Pemerintahan',
                    'uraian' => 'Operasional Pelayanan Kantor & Lembaga',
                    'anggaran' => 150000000,
                    'realisasi' => 140000000,
                ],
                [
                    'tipe' => 'belanja',
                    'kategori' => 'Pelaksanaan Pembangunan',
                    'uraian' => 'Pembangunan Drainase U-Ditch Kraksaan Wetan',
                    'anggaran' => 200000000,
                    'realisasi' => 190000000,
                ],
                [
                    'tipe' => 'pembiayaan',
                    'kategori' => 'Penerimaan Pembiayaan',
                    'uraian' => 'SiLPA Tahun Anggaran 2029',
                    'anggaran' => 30000000,
                    'realisasi' => 30000000,
                ],
                [
                    'tipe' => 'pembiayaan',
                    'kategori' => 'Pengeluaran Pembiayaan',
                    'uraian' => 'Pembentukan Dana Cadangan',
                    'anggaran' => 10000000,
                    'realisasi' => 10000000,
                ],
            ],
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->postJson('/api/admin/transparansi', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.tahun', 2030)
            ->assertJsonPath('data.total_pendapatan_rencana', 450000000)
            ->assertJsonPath('data.total_pendapatan_realisasi', 430000000)
            ->assertJsonPath('data.total_belanja_rencana', 350000000)
            ->assertJsonPath('data.total_belanja_realisasi', 330000000)
            ->assertJsonPath('data.pembiayaan_netto_rencana', 20000000)
            ->assertJsonPath('data.pembiayaan_netto_realisasi', 20000000);

        $this->assertDatabaseCount('anggaran_realisasis', 2);
        $this->assertEquals(6, AnggaranRealisasiItem::where('anggaran_id', $response->json('data.id'))->count());
    }

    public function test_admin_can_update_apbd_and_sync_items(): void
    {
        $budget = AnggaranRealisasi::where('tahun', 2030)->first();
        if (! $budget) {
            $budget = AnggaranRealisasi::create([
                'judul' => 'APBD Test 2030',
                'tahun' => 2030,
                'tanggal_publikasi' => '2030-01-15',
                'status' => 'published',
            ]);
            $budget->items()->create([
                'tipe' => 'pendapatan',
                'kategori' => 'Pendapatan Asli',
                'uraian' => 'Item Lama',
                'anggaran' => 10000000,
                'realisasi' => 10000000,
            ]);
            $budget->recalculateTotals();
        }

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson("/api/admin/transparansi/{$budget->id}", [
                'judul' => 'Anggaran Pendapatan dan Belanja Diperbarui Tahun 2030',
                'tahun' => 2030,
                'tanggal_publikasi' => '2030-02-01',
                'status' => 'published',
                'items' => [
                    [
                        'tipe' => 'pendapatan',
                        'kategori' => 'Pendapatan Transfer',
                        'uraian' => 'Dana Bagi Hasil Pajak Update',
                        'anggaran' => 80000000,
                        'realisasi' => 80000000,
                    ],
                    [
                        'tipe' => 'belanja',
                        'kategori' => 'Penyelenggaraan Pemerintahan',
                        'uraian' => 'Operasional Pelayanan Update',
                        'anggaran' => 60000000,
                        'realisasi' => 55000000,
                    ],
                ],
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.total_pendapatan_rencana', 80000000)
            ->assertJsonPath('data.total_belanja_rencana', 60000000);

        $this->assertDatabaseHas('anggaran_realisasis', [
            'id' => $budget->id,
            'judul' => 'Anggaran Pendapatan dan Belanja Diperbarui Tahun 2030',
        ]);
    }

    private function getOrCreateTestBudget(int $tahun = 2030, string $status = 'published'): AnggaranRealisasi
    {
        $budget = AnggaranRealisasi::where('tahun', $tahun)->first();
        if (! $budget) {
            $budget = AnggaranRealisasi::create([
                'judul' => "Anggaran Pendapatan dan Belanja Test {$tahun}",
                'tahun' => $tahun,
                'tanggal_publikasi' => "{$tahun}-01-15",
                'status' => $status,
                'deskripsi' => 'Deskripsi APBD test pengujian.',
            ]);
            $budget->items()->create([
                'tipe' => 'pendapatan',
                'kategori' => 'Pendapatan Asli',
                'uraian' => 'Hasil Pengelolaan Aset',
                'anggaran' => 50000000,
                'realisasi' => 50000000,
            ]);
            $budget->items()->create([
                'tipe' => 'belanja',
                'kategori' => 'Penyelenggaraan Pemerintahan',
                'uraian' => 'Operasional Kantor',
                'anggaran' => 40000000,
                'realisasi' => 38000000,
            ]);
            $budget->items()->create([
                'tipe' => 'pembiayaan',
                'kategori' => 'Penerimaan Pembiayaan',
                'uraian' => 'SiLPA Tahun Lalu',
                'anggaran' => 10000000,
                'realisasi' => 10000000,
            ]);
            $budget->recalculateTotals();
        } else {
            $budget->update(['status' => $status]);
        }

        return $budget;
    }

    public function test_admin_can_toggle_apbd_status(): void
    {
        $budget = $this->getOrCreateTestBudget(2030, 'published');

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->putJson("/api/admin/transparansi/{$budget->id}/toggle-status", [
                'status' => 'draft',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.status', 'draft');

        $this->assertDatabaseHas('anggaran_realisasis', [
            'id' => $budget->id,
            'status' => 'draft',
        ]);
    }

    public function test_public_cannot_view_draft_apbd_detail(): void
    {
        $budget = $this->getOrCreateTestBudget(2030, 'draft');

        $response = $this->getJson("/api/transparansi/{$budget->slug}");
        $response->assertStatus(404);
    }

    public function test_public_can_view_published_apbd_detail_with_3_sections(): void
    {
        $budget = $this->getOrCreateTestBudget(2030, 'published');

        $response = $this->getJson("/api/transparansi/{$budget->slug}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    'header',
                    'sections' => [
                        'pendapatan' => [
                            'kelompok',
                            'total_anggaran',
                            'total_realisasi',
                            'total_selisih',
                        ],
                        'belanja' => [
                            'kelompok',
                            'total_anggaran',
                            'total_realisasi',
                            'total_selisih',
                            'surplus_defisit_anggaran',
                            'surplus_defisit_realisasi',
                            'surplus_defisit_selisih',
                        ],
                        'pembiayaan' => [
                            'penerimaan',
                            'pengeluaran',
                            'netto',
                            'silpa',
                        ],
                    ],
                    'unduh_url',
                ],
            ]);
    }

    public function test_public_can_download_apbd_pdf_document(): void
    {
        Storage::fake('public');

        $budget = $this->getOrCreateTestBudget(2030, 'published');

        $response = $this->get("/api/transparansi/{$budget->slug}/unduh");

        $response->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_delete_apbd_and_cascade_items(): void
    {
        $budget = $this->getOrCreateTestBudget(2030, 'published');
        $budgetId = $budget->id;

        $response = $this->withHeader('Authorization', 'Bearer '.$this->adminToken)
            ->deleteJson("/api/admin/transparansi/{$budgetId}");

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('anggaran_realisasis', [
            'id' => $budgetId,
        ]);
        $this->assertDatabaseMissing('anggaran_realisasi_items', [
            'anggaran_id' => $budgetId,
        ]);
    }

    protected function tearDown(): void
    {
        TransparansiAnggaran::where('kegiatan', 'Kegiatan Uji Coba Input Realisasi Anggaran Baru')->delete();
        TransparansiAnggaran::where('kegiatan', 'Kegiatan Temp Hapus')->delete();
        TransparansiAnggaran::query()->update(['aktif' => true]);
        AnggaranRealisasi::where('tahun', 2030)->delete();
        parent::tearDown();
    }
}
