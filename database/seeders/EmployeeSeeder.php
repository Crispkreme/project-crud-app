<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Creating 3 admin employees
        for ($i = 1; $i <= 3; $i++) {
            Employee::create([
                'user_id' => $i + 1,
                'referral_id' => $i + 1,
                'name' => "Admin " . $i,
                'address' => $faker->address,
                'age' => $faker->numberBetween(18, 65),
                'position' => "Admin",
            ]);
        }

        // Creating 100 normal employees
        for ($i = 0; $i < 100; $i++) {
            $userId = $faker->numberBetween(4, 103); 
            $referralId = $faker->numberBetween(1, 3); 
            Employee::create([
                'user_id' => $userId,
                'referral_id' => $referralId, 
                'name' => $faker->name,
                'address' => $faker->address,
                'age' => $faker->numberBetween(18, 65),
                'position' => $faker->jobTitle,
            ]);
        }
    }

}
