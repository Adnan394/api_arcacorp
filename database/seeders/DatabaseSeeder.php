<?php

namespace Database\Seeders;

use App\Models\Role;
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
        Role::create([
            'name' => 'Admin',
        ]);
        User::create([
            'username' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role_id' => 1
        ]);
    }
}