<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya satu akaun Pentadbir Sistem dicipta.
        // Pentadbir bertanggungjawab mencipta pengguna lain & menetapkan peranan.
        User::create([
            'name' => 'Pentadbir Sistem',
            'username' => 'admin',
            'password' => '@Bcd1234',
            'role' => User::ROLE_PENTADBIR,
            'is_active' => true,
        ]);
    }
}
