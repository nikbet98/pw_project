<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Models\User;



class ContatInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all user IDs
        $users = User::all();

        foreach($users as $user) {
            DB::table('contat_info')->insert([
                'date_of_birth' => $faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'), // Random date of birth between 18 and 60 years ago
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->streetAddress,
                'zipcode' => $faker->postcode,
                'user_id' => $user->id,
            ]);
        }
    }
}
