<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Master\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

    User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
            'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID otomatis
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('name'),
            'pegawai_uuid' => null, // Admin tidak terkait dengan pegawai
            'role' => '1', // 1= Admin, 2=User
            ]
        );
    }
}