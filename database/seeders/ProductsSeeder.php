<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Brand;
use Faker\Factory as Faker;     

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('it_IT');

        $subcategories = Subcategory::all();
        $brands = Brand::all();

        foreach ($subcategories as $subcategory) {
            for ($i = 0; $i < 10; $i++) {
                Product::create([
                    'name' => $faker->unique()->words(3, true),
                    'description' => $faker->sentence(10),
                    'price' => $faker->randomFloat(2, 10, 1000),
                    'release_date'=> $faker->dateTimeBetween('-5 years','now'),
                    'subcategory_id' => $subcategory->id,
                    'brand_id' => $brands->random()->id,
                    'image' => $faker->imageUrl(640, 480, 'technics'),
                ]);
            }
        }
    }
}
