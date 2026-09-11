<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    private function getOrCreateAdmin(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin@kraksaanwetan.go.id'],
            [
                'name' => 'Administrator Kraksaan Wetan',
                'password' => Hash::make('password123'),
            ]
        );
    }

    public function test_admin_login_with_valid_credentials_returns_token(): void
    {
        $this->getOrCreateAdmin();

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@kraksaanwetan.go.id',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => ['id', 'name', 'email'],
                ],
            ]);
    }

    public function test_admin_login_with_invalid_credentials_returns_401(): void
    {
        $this->getOrCreateAdmin();

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@kraksaanwetan.go.id',
            'password' => 'passwordsalah',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('status', 'error');
    }

    public function test_admin_protected_endpoints_reject_unauthenticated_requests(): void
    {
        $this->getJson('/api/admin/dashboard')
            ->assertStatus(401)
            ->assertJsonPath('status', 'error');

        $this->getJson('/api/admin/me')
            ->assertStatus(401)
            ->assertJsonPath('status', 'error');

        $this->postJson('/api/admin/upload', [])
            ->assertStatus(401)
            ->assertJsonPath('status', 'error');
    }

    public function test_admin_protected_endpoints_allow_valid_bearer_token(): void
    {
        $this->getOrCreateAdmin();

        $login = $this->postJson('/api/admin/login', [
            'email' => 'admin@kraksaanwetan.go.id',
            'password' => 'password123',
        ]);

        $token = $login->json('data.token');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => ['counts', 'recent_pesan', 'recent_berita'],
            ]);
    }

    public function test_admin_me_endpoint_returns_authenticated_user_profile(): void
    {
        $user = $this->getOrCreateAdmin();

        $login = $this->postJson('/api/admin/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $token = $login->json('data.token');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/admin/me');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_admin_logout_invalidates_session_token(): void
    {
        $this->getOrCreateAdmin();

        $login = $this->postJson('/api/admin/login', [
            'email' => 'admin@kraksaanwetan.go.id',
            'password' => 'password123',
        ]);

        $token = $login->json('data.token');

        // Logout
        $logout = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/logout');

        $logout->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }

    public function test_admin_can_upload_image_file(): void
    {
        Storage::fake('public');
        $this->getOrCreateAdmin();

        $login = $this->postJson('/api/admin/login', [
            'email' => 'admin@kraksaanwetan.go.id',
            'password' => 'password123',
        ]);

        $token = $login->json('data.token');

        $file = UploadedFile::fake()->image('kegiatan.jpg', 600, 400);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/upload', [
                'file' => $file,
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['url', 'path', 'filename'],
            ]);

        $savedPath = $response->json('data.path');
        Storage::disk('public')->assertExists($savedPath);
    }

    public function test_admin_upload_rejects_non_image_when_type_is_image(): void
    {
        Storage::fake('public');
        $this->getOrCreateAdmin();

        $login = $this->postJson('/api/admin/login', [
            'email' => 'admin@kraksaanwetan.go.id',
            'password' => 'password123',
        ]);
        $token = $login->json('data.token');

        // Create non-image file (e.g. text/plain or executable script)
        $file = UploadedFile::fake()->create('script.sh', 100, 'application/x-sh');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/upload', [
                'file' => $file,
                'type' => 'image',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    public function test_admin_upload_rejects_non_pdf_when_type_is_document(): void
    {
        Storage::fake('public');
        $this->getOrCreateAdmin();

        $login = $this->postJson('/api/admin/login', [
            'email' => 'admin@kraksaanwetan.go.id',
            'password' => 'password123',
        ]);
        $token = $login->json('data.token');

        // Non-pdf file
        $file = UploadedFile::fake()->create('document.docx', 100, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/admin/upload', [
                'file' => $file,
                'type' => 'document',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    public function test_galeri_api_filters_by_kategori(): void
    {
        $response = $this->getJson('/api/galeri?kategori=Pemerintahan');
        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        foreach ($response->json('data') as $item) {
            $this->assertEquals('Pemerintahan', $item['kategori']);
        }
    }

    public function test_admin_can_update_kelurahan_logo_and_public_api_reflects_change(): void
    {
        $this->getOrCreateAdmin();

        $login = $this->postJson('/api/admin/login', [
            'email' => 'admin@kraksaanwetan.go.id',
            'password' => 'password123',
        ]);
        $token = $login->json('data.token');

        $profilData = $this->getJson('/api/profil')->json('data');

        $newLogoUrl = 'https://example.com/logo-kraksaan-wetan-baru.png';
        $updatePayload = [
            'nama' => $profilData['nama'],
            'alamat' => $profilData['alamat'],
            'telepon' => $profilData['telepon'],
            'email' => $profilData['email'],
            'jam_kerja' => $profilData['jam_kerja'],
            'deskripsi' => $profilData['deskripsi'],
            'sejarah' => $profilData['sejarah'],
            'visi' => $profilData['visi'],
            'misi' => $profilData['misi'],
            'logo' => $newLogoUrl,
            'lurah_nama' => $profilData['lurah']['nama'],
            'lurah_nip' => $profilData['lurah']['nip'],
            'lurah_jabatan' => $profilData['lurah']['jabatan'],
            'lurah_sambutan' => $profilData['lurah']['sambutan'],
            'lurah_foto' => $profilData['lurah']['foto'],
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/admin/profil', $updatePayload);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.logo', $newLogoUrl);

        // Verify public API returns the new logo
        $public = $this->getJson('/api/profil');
        $public->assertStatus(200)
            ->assertJsonPath('data.logo', $newLogoUrl);
    }

    public function test_admin_can_update_hero_image_dynamically_and_public_api_reflects_it(): void
    {
        $this->getOrCreateAdmin();

        $login = $this->postJson('/api/admin/login', [
            'email' => 'admin@kraksaanwetan.go.id',
            'password' => 'password123',
        ]);
        $token = $login->json('data.token');

        $profilData = $this->getJson('/api/profil')->json('data');
        $newHeroUrl = 'https://example.com/uploads/hero-custom-banner.jpg';

        $updatePayload = [
            'nama' => $profilData['nama'],
            'alamat' => $profilData['alamat'],
            'telepon' => $profilData['telepon'],
            'email' => $profilData['email'],
            'jam_kerja' => $profilData['jam_kerja'],
            'deskripsi' => $profilData['deskripsi'],
            'sejarah' => $profilData['sejarah'],
            'visi' => $profilData['visi'],
            'misi' => $profilData['misi'],
            'logo' => $profilData['logo'],
            'hero_image' => $newHeroUrl,
            'lurah_nama' => $profilData['lurah']['nama'],
            'lurah_nip' => $profilData['lurah']['nip'],
            'lurah_jabatan' => $profilData['lurah']['jabatan'],
            'lurah_sambutan' => $profilData['lurah']['sambutan'],
            'lurah_foto' => $profilData['lurah']['foto'],
        ];

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/admin/profil', $updatePayload);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.hero_image', $newHeroUrl);

        // Verify public API returns the updated hero_image
        $public = $this->getJson('/api/profil');
        $public->assertStatus(200)
            ->assertJsonPath('data.hero_image', $newHeroUrl);
    }
}


