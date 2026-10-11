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
        // 1. Akun Admin / Panitia Seleksi
        User::firstOrCreate(
            ['email' => 'admin@ellfirdaus.ac.id'],
            [
                'name' => 'Panitia Seleksi (Admin)',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Pimpinan Pesantren
        User::firstOrCreate(
            ['email' => 'pimpinan@ellfirdaus.ac.id'],
            [
                'name' => 'Pimpinan Pesantren',
                'password' => Hash::make('pimpinan123'),
                'role' => 'pimpinan',
                'phone' => '081298765432',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 3. Konfigurasi Variabel Fuzzy & Basis Aturan FAM
        $this->call(FuzzyParameterSeeder::class);
    }
}
