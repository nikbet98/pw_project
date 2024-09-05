<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subcategory;
use app\Models\Category;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(){

        $subcategories = [
            'Schede Madri' => 'Componenti PC',
            'Processori' => 'Componenti PC',
            'Tastiere' => 'Periferiche',
            'Mouse' => 'Periferiche',
            'SSD' => 'Storage',
            'Router' => 'Networking',
        ];

        foreach ($subcategories as $name => $categoryName) {
            $category = Category::where('name', $categoryName)->first();
            Subcategory::create(['name' => $name, 'category_id' => $category->id]);
        }
    }
}
