<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotion;
use Faker\Factory as Faker;
use App\Models\Product;



class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('it_IT');

        // Create 5 promotions
        for ($i = 0; $i < 5; $i++) {
            $promotion =Promotion::create([
                'name' => $faker->unique()->catchPhrase,
                'description' => $faker->text,
                'start' => $faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
                'end' => $faker->dateTimeBetween('+1 month', '+3 months')->format('Y-m-d'),
                'image' => $faker->imageUrl(640, 480, 'technics'), // Generate a random image URL
            ]);

            // Associate random products with the promotion
            $products = Product::inRandomOrder()->limit(rand(2, 5))->get(); // Get 2-5 random products
            $promotion->products()->sync($products);

            foreach ($promotion->products as $product) {
                $discount = $faker->randomFloat(2, 0, 1); // Generate a random discount between 0% and 20%
                $product->promotion()->updateExistingPivot($promotion->id, ['discount' => $discount]);
            }
        }
    }
}
