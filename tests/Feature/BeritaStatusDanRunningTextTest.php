<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class BeritaStatusDanRunningTextTest extends TestCase
{
    protected User $superAdmin;

    protected string $superAdminToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::firstOrCreate(
            ['email' => 'admin_test_berita@kraksaanwetan.go.id'],
            [
                'name' => 'Super Admin Test Berita',
                'password' => bcrypt('password123'),
                'role' => User::ROLE_SUPER_ADMIN,
            ]
        );
        $this->superAdminToken = base64_encode($this->superAdmin->id.'|'.Str::random(32).'|'.time());
        Cache::put('admin_auth_token_'.$this->superAdminToken, $this->superAdmin->id, now()->addDays(7));
    }

    public function test_public_berita_only_returns_published_news(): void
    {
        $publishedNews = Berita::create([
            'slug' => 'berita-publik-'.Str::random(8),
            'judul' => 'Berita Terbit Publik',
            'kategori' => 'Umum',
            'tanggal' => '28 September 2026',
            'penulis' => 'Humas',
            'ringkasan' => 'Ringkasan publik',
            'konten' => 'Konten publik lengkap',
            'status' => 'published',
            'tampil_running_text' => true,
        ]);

        $draftNews = Berita::create([
            'slug' => 'berita-draft-'.Str::random(8),
            'judul' => 'Berita Konsep Rahasia',
            'kategori' => 'Umum',
            'tanggal' => '28 September 2026',
            'penulis' => 'Humas',
            'ringkasan' => 'Ringkasan draft',
            'konten' => 'Konten draft lengkap',
            'status' => 'draft',
            'tampil_running_text' => false,
        ]);

        $response = $this->getJson('/api/berita');
        $response->assertStatus(200);

        $slugs = collect($response->json('data'))->pluck('slug')->all();

        $this->assertContains($publishedNews->slug, $slugs);
        $this->assertNotContains($draftNews->slug, $slugs);
    }

    public function test_running_text_endpoint_returns_only_active_published_running_text_news(): void
    {
        $runningNews = Berita::create([
            'slug' => 'berita-running-'.Str::random(8),
            'judul' => 'Warta Penting Berjalan',
            'kategori' => 'Penting',
            'tanggal' => '28 September 2026',
            'penulis' => 'Humas',
            'ringkasan' => 'Ringkasan running',
            'konten' => 'Konten running',
            'status' => 'published',
            'tampil_running_text' => true,
        ]);

        $noRunningNews = Berita::create([
            'slug' => 'berita-no-running-'.Str::random(8),
            'judul' => 'Warta Biasa Tanpa Running',
            'kategori' => 'Penting',
            'tanggal' => '28 September 2026',
            'penulis' => 'Humas',
            'ringkasan' => 'Ringkasan',
            'konten' => 'Konten',
            'status' => 'published',
            'tampil_running_text' => false,
        ]);

        $draftRunningNews = Berita::create([
            'slug' => 'berita-draft-running-'.Str::random(8),
            'judul' => 'Warta Draft Tapi Running',
            'kategori' => 'Penting',
            'tanggal' => '28 September 2026',
            'penulis' => 'Humas',
            'ringkasan' => 'Ringkasan',
            'konten' => 'Konten',
            'status' => 'draft',
            'tampil_running_text' => true,
        ]);

        $response = $this->getJson('/api/running-text');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $slugs = collect($response->json('data'))->pluck('slug')->all();

        $this->assertContains($runningNews->slug, $slugs);
        $this->assertNotContains($noRunningNews->slug, $slugs);
        $this->assertNotContains($draftRunningNews->slug, $slugs);
    }

    public function test_admin_can_toggle_berita_status_and_running_text(): void
    {
        $berita = Berita::create([
            'slug' => 'berita-toggle-'.Str::random(8),
            'judul' => 'Berita Toggle Admin',
            'kategori' => 'Umum',
            'tanggal' => '28 September 2026',
            'penulis' => 'Humas',
            'ringkasan' => 'Ringkasan toggle',
            'konten' => 'Konten toggle',
            'status' => 'draft',
            'tampil_running_text' => false,
        ]);

        // Toggle Status
        $resStatus = $this->withHeader('Authorization', 'Bearer '.$this->superAdminToken)
            ->putJson('/api/admin/berita/'.$berita->id.'/toggle-status');

        $resStatus->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.status', 'published');

        $this->assertDatabaseHas('beritas', [
            'id' => $berita->id,
            'status' => 'published',
        ]);

        // Toggle Running Text
        $resRunning = $this->withHeader('Authorization', 'Bearer '.$this->superAdminToken)
            ->putJson('/api/admin/berita/'.$berita->id.'/toggle-running-text');

        $resRunning->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.tampil_running_text', true);

        $this->assertDatabaseHas('beritas', [
            'id' => $berita->id,
            'tampil_running_text' => true,
        ]);
    }
}
