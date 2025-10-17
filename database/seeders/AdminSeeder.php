<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!Admin::where('username', 'Admin-2025')->exists()) {
            Admin::create([
                'username' => 'Admin-2025',
                'password' => Hash::make('@dmin_0!_2025*'),
            ]);
        }
    }
}
