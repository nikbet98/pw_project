<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Promotion;
use App\Models\DataLayer;




class HomeController extends Controller
{
    // In your HomeController or a relevant controller
    public function index() {
        $dl = new DataLayer();
        
        $bestProducts = $dl->bestProducts();

        $latestProducts = $dl->latestProducts();
        
        $promotions = $dl->listPromotions();

        $brands = $dl->listBrands();

        $categories = $dl->listCategories();

        return view('home', compact('bestProducts', 'latestProducts', 'promotions', 'brands', 'categories'));
    }

}
