<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create(['name' => 'Admin User', 'email' => 'admin@test.com', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'id_level' => 1]);
        \App\Models\User::create(['name' => 'Operator User', 'email' => 'operator@test.com', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'id_level' => 2]);
        \App\Models\User::create(['name' => 'Pimpinan User', 'email' => 'pimpinan@test.com', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'id_level' => 3]);
    }
}
