<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 20 مالك
        User::factory()->count(20)->create([
            'user_type' => 'owner',
        ]);

        // 20 مستأجر
        User::factory()->count(20)->create([
            'user_type' => 'tenant',
        ]);
    }
}
