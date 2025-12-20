<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'phone' => '0999999999',
            'password' => Hash::make('admin@2025'),
            'date_of_birth' => '2005-01-01',
            'user_type' => 'admin',
            'status' => 'approved',
            'profile_image' => 'admin.png',
            'id_image' => 'admin_id.png',
        ]);
    }
}
