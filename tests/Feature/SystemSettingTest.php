<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SystemSettingTest extends TestCase
{
    private function getOrCreateAdmin(string $role = 'super_admin'): User
    {
        return User::create([
            'name' => 'User Test '.uniqid(),
            'email' => 'user_'.uniqid().'@kraksaanwetan.go.id',
            'password' => Hash::make('password123'),
            'role' => $role,
            'is_active' => true,
        ]);
    }

    private function getUserToken(string $role = 'super_admin'): string
    {
        $user = $this->getOrCreateAdmin($role);
        $login = $this->postJson('/api/admin/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        return $login->json('data.token');
    }

    public function test_super_admin_can_create_and_manage_halaman_kustom(): void
    {
        $token = $this->getUserToken('super_admin');

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/halaman-kustom', [
                'judul' => 'Prestasi Kelurahan 2026',
                'kategori' => 'profil',
                'ringkasan' => 'Daftar raihan juara dan apresiasi tingkat nasional.',
                'konten' => '<p>Kelurahan Kraksaan Wetan meraih juara 1 inovasi digital.</p>',
                'aktif' => true,
                'urutan' => 1,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.slug', 'prestasi-kelurahan-2026');

        $this->assertDatabaseHas('halaman_kustoms', [
            'judul' => 'Prestasi Kelurahan 2026',
            'slug' => 'prestasi-kelurahan-2026',
            'kategori' => 'profil',
        ]);

        $createdId = $response->json('data.id');

        // Test update
        $updateResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/admin/halaman-kustom/{$createdId}", [
                'judul' => 'Prestasi Unggulan Kelurahan',
                'slug' => 'prestasi-unggulan',
                'kategori' => 'profil',
                'ringkasan' => 'Daftar prestasi terupdate.',
                'konten' => '<p>Konten baru diperbarui.</p>',
                'aktif' => true,
            ]);

        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.slug', 'prestasi-unggulan');

        // Test public view endpoint
        $publicResponse = $this->getJson('/api/halaman/prestasi-unggulan');
        $publicResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.judul', 'Prestasi Unggulan Kelurahan');

        // Test delete
        $deleteResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/admin/halaman-kustom/{$createdId}");
        $deleteResponse->assertStatus(200);

        $this->assertDatabaseMissing('halaman_kustoms', [
            'id' => $createdId,
        ]);
    }

    public function test_non_super_admin_cannot_manage_halaman_kustom(): void
    {
        $token = $this->getUserToken('staff_konten');

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/admin/halaman-kustom', [
                'judul' => 'Uji Coba Ilegal',
                'kategori' => 'profil',
            ]);

        $response->assertStatus(403);
    }

    public function test_super_admin_can_update_logo_and_hero_settings_partially(): void
    {
        $token = $this->getUserToken('super_admin');

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson('/api/admin/profil', [
                'logo' => 'https://example.com/logo-baru.png',
                'hero_image' => 'https://example.com/hero-baru.jpg',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.logo', 'https://example.com/logo-baru.png')
            ->assertJsonPath('data.hero_image', 'https://example.com/hero-baru.jpg');

        $public = $this->getJson('/api/profil');
        $public->assertStatus(200)
            ->assertJsonPath('data.logo', 'https://example.com/logo-baru.png')
            ->assertJsonPath('data.hero_image', 'https://example.com/hero-baru.jpg');
    }
}
