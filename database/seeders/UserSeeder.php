<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@coffe.com',
            'password' => Hash::make('password'), // Ganti dengan password aman
            'role' => 'admin',
        ]);

        // User biasa
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@coffe.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}