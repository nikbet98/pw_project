<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 
use App\models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\DataLayer;
use Illuminate\Support\Facades\DB;





class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;

        $dl = new DataLayer();
        $cart = $dl->userCart($userId);
        $products = $dl->cartProducts($cart->id);
        $categories = $dl->listCategories();
        $promotions = $dl->listPromotions();
        $contactInfo = Auth::user()->contactInfo;


        return view('user.checkout', compact('cart', 'products', 'categories','promotions','contactInfo')); 
    }
    
    public function processPayment(Request $request)
    {
        // 1. Validate the form data (shipping address, payment details, etc.)
        $validatedData = $request->validate([
            'shipping_address' => 'required|string|max:255', // Address validation
            'credit_card_number' => 'required|string|size:16', // Basic credit card number (16 digits)
            'expiry_month' => 'required|integer|min:1|max:12', // Expiry month
            'expiry_year' => 'required|integer|min:' . date('Y'), // Expiry year (must be current year or later)
            'cvv' => 'required|string|size:3', // CVV (3 digits)
        ]);


        $order = Order::create([
            'user_id' => Auth::user()->id,
            'total' => $request->input('total'), 
            'state' => 'pending', 
            'date' => now(),
        ]);

        $dl = new DataLayer();
            $cart = $dl->userCart(Auth::user()->id);
            $cartProduct = $dl->cartProducts($cart->id);

            foreach ($cartProduct as $product) {
                $cartQuantity = $cart->products()->where('product_id', $product->id)->first()->pivot->quantity;
                $order->products()->attach($product->id, [
                    'quantity' => $cartQuantity,
                    'price' => $product->price,
                    'discount' => $dl->getProductDiscount($product->id)
                ]);
            }

        $cart->products()->detach();
    
        return response()->json(['success' => true, 'redirect' => route('profile')]);

    }
}
