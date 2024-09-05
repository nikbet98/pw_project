<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Promotion;

class Helpers
{
    public static function getDiscountedPrice(Product $product, Promotion $promotion)
    {
        $discount = $product->promotion()->withPivot('discount')->where('promotion_id', $promotion->id)->first()->pivot->discount;
        $discountedPrice = $product->price - ($product->price * ($discount));
        $discountedPrice = number_format($discountedPrice, 2, '.', ''); 
        return [
            'discountedPrice' => $discountedPrice,
            'discount' => $discount,
        ];
    }

    public static function getProductDiscount($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return 0;
        }
        
        $activePromotion = $product->promotion()
                                  ->first();

        if ($activePromotion) {
            return $activePromotion->pivot->discount; 
        }
        return 0;
    }
}
