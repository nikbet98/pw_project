<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $this->call([
            CategorySeeder::class,
            SubcategorySeeder::class,
            BrandsSeeder::class,
            ProductsSeeder::class,
            ReviewsSeeder::class,
            PromotionSeeder::class,
            ContatInfoSeeder::class,
            AdminUserSeeder::class,
        ]);


    }
}
