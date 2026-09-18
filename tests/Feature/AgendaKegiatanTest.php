<?php

namespace Tests\Feature;

use App\Models\AgendaKegiatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AgendaKegiatanTest extends TestCase
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

    public function test_public_agenda_auto_hides_expired_events_by_default(): void
    {
        $now = Carbon::now();

        // 1. Agenda masa depan (akan datang)
        $upcoming = AgendaKegiatan::create([
            'judul' => 'Musrenbang Kelurahan Mendatang '.uniqid(),
            'slug' => 'musrenbang-mendatang-'.uniqid(),
            'deskripsi' => '<p>Deskripsi musrenbang</p>',
            'tanggal_mulai' => $now->copy()->addDays(2)->setHour(8),
            'tanggal_selesai' => $now->copy()->addDays(2)->setHour(12),
            'lokasi' => 'Balai Kelurahan',
            'is_aktif' => true,
        ]);

        // 2. Agenda yang sedang berlangsung
        $ongoing = AgendaKegiatan::create([
            'judul' => 'Kegiatan Sedang Berlangsung '.uniqid(),
            'slug' => 'sedang-berlangsung-'.uniqid(),
            'deskripsi' => '<p>Deskripsi kegiatan</p>',
            'tanggal_mulai' => $now->copy()->subHours(1),
            'tanggal_selesai' => $now->copy()->addHours(2),
            'lokasi' => 'Balai Pertemuan',
            'is_aktif' => true,
        ]);

        // 3. Agenda masa lampau (sudah selesai -> otomatis harus hilang dari tampilan publik aktif)
        $expired = AgendaKegiatan::create([
            'judul' => 'Agenda Kemarin Sudah Selesai '.uniqid(),
            'slug' => 'agenda-kemarin-'.uniqid(),
            'deskripsi' => '<p>Deskripsi kemarin</p>',
            'tanggal_mulai' => $now->copy()->subDays(3),
            'tanggal_selesai' => $now->copy()->subDays(2),
            'lokasi' => 'Lapangan',
            'is_aktif' => true,
        ]);

        // 4. Agenda nonaktif (draft)
        $inactive = AgendaKegiatan::create([
            'judul' => 'Agenda Nonaktif '.uniqid(),
            'slug' => 'agenda-nonaktif-'.uniqid(),
            'deskripsi' => '<p>Draft</p>',
            'tanggal_mulai' => $now->copy()->addDays(5),
            'tanggal_selesai' => $now->copy()->addDays(5)->addHours(2),
            'is_aktif' => false,
        ]);

        // Request publik default (hanya yang aktif dan belum selesai)
        $response = $this->getJson('/api/agenda');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $data = $response->json('data');
        $returnedIds = collect($data)->pluck('id')->all();

        // Pastikan agenda masa depan dan sedang berlangsung muncul
        $this->assertContains($upcoming->id, $returnedIds);
        $this->assertContains($ongoing->id, $returnedIds);

        // Pastikan agenda yang sudah selesai OTOMATIS HILANG
        $this->assertNotContains($expired->id, $returnedIds);

        // Pastikan agenda nonaktif tidak muncul
        $this->assertNotContains($inactive->id, $returnedIds);

        // Request publik dengan tab riwayat (tampilkan_riwayat=1)
        $riwayatRes = $this->getJson('/api/agenda?tampilkan_riwayat=1');
        $riwayatRes->assertStatus(200);
        $riwayatIds = collect($riwayatRes->json('data'))->pluck('id')->all();

        // Agenda yang sudah selesai harus muncul di riwayat
        $this->assertContains($expired->id, $riwayatIds);
        // Agenda masa depan tidak boleh muncul di riwayat
        $this->assertNotContains($upcoming->id, $riwayatIds);

        // Cleanup
        $upcoming->delete();
        $ongoing->delete();
        $expired->delete();
        $inactive->delete();
    }

    public function test_public_agenda_detail_by_slug(): void
    {
        $now = Carbon::now();
        $agenda = AgendaKegiatan::create([
            'judul' => 'Pelatihan UMKM Kraksaan '.uniqid(),
            'slug' => 'pelatihan-umkm-'.uniqid(),
            'deskripsi' => '<p>Pelatihan kemasan dan pemasaran digital</p>',
            'tanggal_mulai' => $now->copy()->addDay(),
            'tanggal_selesai' => $now->copy()->addDay()->addHours(4),
            'lokasi' => 'Aula Kelurahan',
            'is_aktif' => true,
        ]);

        $response = $this->getJson('/api/agenda/'.$agenda->slug);
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.judul', $agenda->judul);

        $agenda->delete();
    }

    public function test_admin_agenda_crud_lifecycle(): void
    {
        $token = $this->getAdminToken();
        $now = Carbon::now();

        // 1. Create
        $createRes = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/agenda', [
                'judul' => 'Musyawarah Warga RW 03 Baru '.uniqid(),
                'kategori' => 'Sosial & Kemasyarakatan',
                'penyelenggara' => 'Pengurus RW 03',
                'lokasi' => 'Balai RW 03',
                'tanggal_mulai' => $now->copy()->addDays(3)->format('Y-m-d H:i:s'),
                'tanggal_selesai' => $now->copy()->addDays(3)->addHours(3)->format('Y-m-d H:i:s'),
                'deskripsi' => '<p>Rembuk warga mengenai lingkungan bersih dan pos kamling.</p>',
                'is_aktif' => true,
            ]);

        $createRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $agendaId = $createRes->json('data.id');
        $this->assertNotNull($agendaId);

        // 2. Read (admin list)
        $listRes = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/agenda');

        $listRes->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data',
                'counts' => [
                    'total',
                    'berlangsung',
                    'akan_datang',
                    'selesai',
                    'nonaktif',
                ],
            ]);

        // 3. Update
        $updateRes = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/admin/agenda/'.$agendaId, [
                'judul' => 'Musyawarah Warga RW 03 (Diperbarui)',
                'kategori' => 'Sosial & Kemasyarakatan',
                'penyelenggara' => 'Pengurus RW 03 & RT',
                'lokasi' => 'Balai RW 03 Kraksaan Wetan',
                'tanggal_mulai' => $now->copy()->addDays(4)->format('Y-m-d H:i:s'),
                'tanggal_selesai' => $now->copy()->addDays(4)->addHours(3)->format('Y-m-d H:i:s'),
                'deskripsi' => '<p>Deskripsi yang telah disunting.</p>',
                'is_aktif' => true,
            ]);

        $updateRes->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.judul', 'Musyawarah Warga RW 03 (Diperbarui)');

        // 4. Toggle Aktif
        $toggleRes = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/admin/agenda/'.$agendaId.'/toggle-aktif');

        $toggleRes->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.is_aktif', false);

        // 5. Delete
        $deleteRes = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/admin/agenda/'.$agendaId);

        $deleteRes->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('agenda_kegiatans', ['id' => $agendaId]);
    }

    public function test_agenda_status_accessors(): void
    {
        $now = Carbon::now();

        $agendaBerlangsung = new AgendaKegiatan([
            'judul' => 'Agenda Hari Ini',
            'tanggal_mulai' => $now->copy()->subMinutes(30),
            'tanggal_selesai' => $now->copy()->addHours(2),
            'is_aktif' => true,
        ]);
        $this->assertEquals('berlangsung', $agendaBerlangsung->status_agenda);
        $this->assertEquals('Sedang Berlangsung', $agendaBerlangsung->status_label);
        $this->assertFalse($agendaBerlangsung->is_expired);

        $agendaAkanDatang = new AgendaKegiatan([
            'judul' => 'Agenda Besok',
            'tanggal_mulai' => $now->copy()->addDays(2),
            'tanggal_selesai' => $now->copy()->addDays(2)->addHours(2),
            'is_aktif' => true,
        ]);
        $this->assertEquals('akan_datang', $agendaAkanDatang->status_agenda);
        $this->assertEquals('Akan Datang', $agendaAkanDatang->status_label);
        $this->assertFalse($agendaAkanDatang->is_expired);

        $agendaSelesai = new AgendaKegiatan([
            'judul' => 'Agenda Kemarin',
            'tanggal_mulai' => $now->copy()->subDays(2),
            'tanggal_selesai' => $now->copy()->subDay(),
            'is_aktif' => true,
        ]);
        $this->assertEquals('selesai', $agendaSelesai->status_agenda);
        $this->assertEquals('Selesai', $agendaSelesai->status_label);
        $this->assertTrue($agendaSelesai->is_expired);
    }
}
