<?php

namespace Database\Seeders;

use App\Models\Gov;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $govs = [
            'Damascus',                // Corresponds to gov_id: 1
            'Damascus Countryside',    // Corresponds to gov_id: 2
            'Aleppo',                  // Corresponds to gov_id: 3
            'Homs',                    // Corresponds to gov_id: 4
            'Hama',                    // Corresponds to gov_id: 5
            'Lattakia',                // Corresponds to gov_id: 6
            'Tartus',                  // Corresponds to gov_id: 7
            'Daraa',                   // Corresponds to gov_id: 8
            'As-Suwayda',              // Corresponds to gov_id: 9
            'Quneitra',                // Corresponds to gov_id: 10
            'Al-Hasakah',              // Corresponds to gov_id: 11
            'Deir ez-Zor',             // Corresponds to gov_id: 12
            'Idlib',                   // Corresponds to gov_id: 13
            'Raqqa',                   // Corresponds to gov_id: 14
        ];

        foreach ($govs as $governorate) {
            Gov::create(['name' => $governorate]);
        }
    }
}
