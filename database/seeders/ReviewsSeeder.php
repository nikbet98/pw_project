<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Faker\Factory as Faker;

class ReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('it_IT');
        $products = Product::all();
        $users = User::all();

        foreach ($products as $product) {
            foreach ($users as $user) {
                // Genera un numero casuale di recensioni per ciascun prodotto
                for ($i = 0; $i < rand(1, 5); $i++) {
                    Review::create([
                        'text' => $faker->paragraph,
                        'title'=> $faker->sentence(5),
                        'stars' => $faker->numberBetween(1, 5),
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'created_at' => $faker->dateTimeBetween('-5 years','now'),
                    ]);
                }
            }
        }
    }
}
