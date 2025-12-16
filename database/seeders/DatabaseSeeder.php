<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gov;
use App\Models\City;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([govsyseeder::class, cityseeder::class, AdminSeeder::class]);
    }
}
