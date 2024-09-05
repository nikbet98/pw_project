<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataLayer;
use App\Models\Promotion;



class promotionController extends Controller
{
    public function index(){
        $dl = new DataLayer();
        $promotions = $dl->activePromotions();
        $categories = $dl->listCategories();
        
        return view('promotion.promotions')
            ->with('promotions',$promotions)
            ->with('categories',$categories);
    }

    public function show(Promotion $promotion){
        $dl = new DataLayer();
        $categories = $dl->listCategories();
        $products = $dl->productInPromotion($promotion);
        return view('promotion.promotion', compact('promotion', 'products','categories'));
    }
}
