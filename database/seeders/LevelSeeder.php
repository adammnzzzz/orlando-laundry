<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Level::create(['level_name' => 'Admin']);
        \App\Models\Level::create(['level_name' => 'Operator']);
        \App\Models\Level::create(['level_name' => 'Pimpinan']);
    }
}
