<?php

namespace App\Models;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;




class SessionCart
{
    protected $items = [];

    public function __construct() {

        $this->items = Session::get('cart', []);
    }

    public function add($productId, $quantity = 1, $discountedPrice = null) {

        if (Auth::check()) {
            $this->addToDatabaseCart($productId, $quantity, $discountedPrice);
        } else {
            if (isset($this->items[$productId])) {
                $this->items[$productId]['quantity'] += $quantity;
            } else {
                $this->items[$productId] = [
                    'quantity' => $quantity,
                    'discountedPrice' => $discountedPrice,
                ];
            }   
        }
        $this->save();
    }

    protected function addToDatabaseCart($productId, $quantity, $discountedPrice = null)
    {
        // 1. Get the User's Cart ID
        $cartId = $this->getCartIdForUser(); // You'll need to implement this method

        // 2. Check if the Product is Already in the Cart
        $cartItem = DB::table('product_cart')
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // 3a. If Product Exists, Update Quantity
            DB::table('product_cart')
                ->where('id', $cartItem->id) 
                ->update(['quantity' => $cartItem->quantity + $quantity]);
        } else {
            // 3b. If Product Doesn't Exist, Create a New Entry
            DB::table('product_cart')->insert([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'discounted_price' => $discountedPrice,
            ]);
        }
    }

    public function getItemsFromDatabase()
    {
        $cartId = $this->getCartIdForUser();

        $cartItems = DB::table('product_cart')
            ->where('cart_id', $cartId)
            ->get();

        // Format the data to match your session cart structure (if needed)
        $formattedCartItems = [];
        foreach ($cartItems as $cartItem) {
            $formattedCartItems[$cartItem->product_id] = [
                'quantity' => $cartItem->quantity,
                'discountedPrice' => $cartItem->discounted_price,
            ];
        }

        return collect($formattedCartItems);
    }

    

    protected function getCartIdForUser() {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]); 
        return $cart->id; 
    }

    public function remove($productId)
    {
        if (Auth::check()) {
            $cartId = $this->getCartIdForUser();
            DB::table('product_cart')
                ->where('cart_id', $cartId)
                ->where('product_id', $productId)
                ->delete();
        } else {
            if (isset($this->items[$productId])) {
                unset($this->items[$productId]);
                $this->save(); // Save session cart changes
            }
        }
    }

    public function update($productId, $quantity)
    {
        if (Auth::check()) {
            $cartId = $this->getCartIdForUser();
            DB::table('product_cart')
                ->where('cart_id', $cartId)
                ->where('product_id', $productId)
                ->update(['quantity' => $quantity]);
        } else {
            if (isset($this->items[$productId])) {
                $this->items[$productId]['quantity'] = $quantity;
                $this->save(); // Save session cart changes
            }
        }   
    }

    public function getItems()
    {
        return $this->items;
    }

    public function clear()
    {
        $this->items = [];
        $this->save();
    }

    protected function save()
    {
        Session::put('cart', $this->items);
    }

    public function totalItems()
    {
        return array_sum(array_column($this->items, 'quantity'));
    }

    public function count(){
        $totalQuantity = 0;
        foreach ($this->items as $item) {
            $totalQuantity += $item['quantity'];
        }
        return $totalQuantity;
    }

    public function getItemQuantity($productId){
        return $this->items[$productId]['quantity'];
    }

    public function getTotalCost() {
        
        $totalCost = 0;

        if(Auth::check()){
            $cartId = $this->getCartIdForUser();
            $databaseCartItems = DB::table('product_cart')
                ->where('cart_id', $cartId)
                ->get();
    
            foreach ($databaseCartItems as $item) {
                $price = $item->discounted_price ?? Product::find($item->product_id)->price;
                $totalCost += $price * $item->quantity;
            }
        }else{
            foreach ($this->items as $productId => $item) {
                $quantity = $item['quantity'];
                $price = $item['discountedPrice'] ?? Product::find($productId)->price; 
                $totalCost += $price * $quantity;
            }
        }
        return $totalCost;
    }

}
