<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Super Admin Utama Kelurahan Kraksaan Wetan
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@kraksaanwetan.go.id'],
            [
                'name' => 'Administrator Kraksaan Wetan',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_SUPER_ADMIN,
            ]
        );
        $superAdmin->update(['role' => User::ROLE_SUPER_ADMIN]);

        // 2. Akun Staf Konten & Humas
        User::firstOrCreate(
            ['email' => 'humas@kraksaanwetan.go.id'],
            [
                'name' => 'Staf Konten & Publikasi Humas',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_STAFF_KONTEN,
            ]
        );

        // 3. Akun Staf Pelayanan Masyarakat
        User::firstOrCreate(
            ['email' => 'pelayanan@kraksaanwetan.go.id'],
            [
                'name' => 'Staf Pelayanan Terpadu',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_STAFF_PELAYANAN,
            ]
        );

        // 4. Akun Staf Administrasi & Lembaga
        User::firstOrCreate(
            ['email' => 'administrasi@kraksaanwetan.go.id'],
            [
                'name' => 'Staf Administrasi Wilayah & LKK',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_STAFF_ADMINISTRASI,
            ]
        );

        // Jalankan seeder konten kelurahan
        $this->call([
            KelurahanSeeder::class,
            LembagaSeeder::class,
            TransparansiAnggaranSeeder::class,
        ]);
    }
}
