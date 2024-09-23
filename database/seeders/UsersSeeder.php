<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Creating 3 admin employees
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'email' => 'admin' . ($i + 1) . '@admin' . ($i + 1) . '.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'admin',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Creating 100 employees
        for ($i = 1; $i <= 100; $i++) {
            User::create([
                'email' => 'employee' . ($i + 1) . '@employee' . ($i + 1) . '.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'employee',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
