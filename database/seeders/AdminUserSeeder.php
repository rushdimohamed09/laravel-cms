<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@alternative.agency',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
            'user_id' => 1,
            'approvedBy' => 1,
            'approved_at' => now(),
        ]);
    }
}
