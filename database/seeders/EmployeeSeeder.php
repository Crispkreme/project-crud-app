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

        for ($i = 0; $i < 100; $i++) {
            Employee::create([
                'name' => $faker->name,
                'address' => $faker->address,
                'age' => $faker->numberBetween($min = 18, $max = 65),
                'position' => $faker->jobTitle,
            ]);
        }
    }
}
