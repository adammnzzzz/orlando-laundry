<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeOfServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TypeOfService::create(['service_name' => 'Cuci dan Gosok', 'price' => 5000]);
        \App\Models\TypeOfService::create(['service_name' => 'Hanya Cuci', 'price' => 4500]);
        \App\Models\TypeOfService::create(['service_name' => 'Hanya Gosok', 'price' => 5000]);
        \App\Models\TypeOfService::create(['service_name' => 'Laundry Besar', 'price' => 7000]);
    }
}
