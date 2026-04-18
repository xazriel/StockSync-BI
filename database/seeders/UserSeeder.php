<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun untuk Rafi
        User::create([
            'name' => 'Rafi',
            'email' => 'rafi@a.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Akun untuk Bintang
        User::create([
            'name' => 'Bintang',
            'email' => 'bintang@a.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);
    }
}