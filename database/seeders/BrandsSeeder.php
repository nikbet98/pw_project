<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Faker\Factory as Faker;



class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $brands = ['Intel', 'AMD', 'Logitech', 'Asus', 'Samsung'];
        $faker = Faker::create('it_IT');

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'image' => $faker->imageUrl(640, 480, 'technics'),
            ]);
        }
    }
}
