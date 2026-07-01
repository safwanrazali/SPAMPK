<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Satu akaun bagi setiap peranan dicipta semasa pemasangan.
        // Pentadbir boleh mencipta/menyunting akaun lain selepas log masuk.
        User::create([
            'name' => 'Pentadbir Sistem',
            'username' => 'admin',
            'password' => '@Bcd1234',
            'role' => User::ROLE_PENTADBIR,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Pengurusan',
            'username' => 'pengurusan',
            'password' => '@Bcd1234',
            'role' => User::ROLE_PENGURUSAN,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Pegawai Penyelaras Analisis',
            'username' => 'penyelaras',
            'password' => '@Bcd1234',
            'role' => User::ROLE_PENYELARAS,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Pegawai Analisis',
            'username' => 'analisis',
            'password' => '@Bcd1234',
            'role' => User::ROLE_ANALISIS,
            'is_active' => true,
        ]);
    }
}