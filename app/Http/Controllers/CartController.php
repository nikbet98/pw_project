<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SessionCart;
use App\Models\DataLayer;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Models\Promotion;



class CartController extends Controller
{
    protected $cart;

    public function __construct(SessionCart $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        $dl = new DataLayer();
        $categories = $dl->listCategories();
        
        if (Auth::check()) {
            $cartItems = $this->cart->getItemsFromDatabase(); // Get from database
        } else {
            $cartItems = $this->cart->getItems(); // Get from session
        }
        // Calcola il costo totale del carrello
        $cart = new SessionCart();
        $totalCartCost = $cart->getTotalCost();


        // Ottieni i dettagli del prodotto per visualizzare nel carrello
        $products = Product::whereIn('id', $cartItems->keys())->get(); 

        return view('cart', compact('cartItems', 'products','categories', 'totalCartCost'));
    }

    public function add(Request $request){

        $dl = new DataLayer();
        $productId = $request->input('product_id');
        $product = $dl->findProduct($productId);


        $discountedPrice = null;
        if ($product->promotion->isNotEmpty()) {
            // Assuming the first promotion is the active one
            $promotion = $product->promotion->first(); 
            $discountData = Helpers::getDiscountedPrice($product, $promotion);
            $discountedPrice = $discountData['discountedPrice'];
        }
        $this->cart->add($productId, 1, $discountedPrice);

        return response()->json(['cart_count' => $this->cart->count(), 'success' => true]); 
    }

    public function remove($productId) {

        $this->cart->remove($productId);
        $totalCartCost = $this->cart->getTotalCost();

        // return redirect()->route('cart')->with('success', 'Prodotto rimosso dal carrello!');
        return response()->json([
            'success' => true,
            'totalCartCost' => $totalCartCost
        ]);
    }

    public function update(Request $request, $productId)
    {
        
        $this->cart->update($productId, $request->input('quantity'));
        
        $itemTotal = $this->getItemTotal($productId);
        $totalCartCost = $this->cart->getTotalCost();


        return response()->json([
            'success' => true,
            'itemTotal' => $itemTotal,
            'totalCartCost' => $totalCartCost
        ]);
        
    }

    public function clear()
    {
        $this->cart->clear();

        if (Auth::check()) { 
            $dl = new DataLayer();
            $cart = $dl->userCart(Auth::user()->id);
            if ($cart) {
                $cart->products()->detach(); // Remove product associations from the cart
            }
        }

        return response()->json(['success' => true]);
    }

    public function count(){
        $cartCount = $this->cart->count();
        return response()->json(['cart_count' => $cartCount]);
    }

    public function getTotalCartCount()
    {
        $totalCount = 0;

        // 1. Get Session Cart Count
        $totalCount += $this->cart->count();

        // 2. Get Database Cart Count (if user is logged in)
        if (Auth::check()) {
            $dl = new DataLayer();
            $cart = $dl->userCart(Auth::user()->id); 
            if ($cart) {
                $totalCount = $cart->products()->sum('product_cart.quantity'); 
            }
        }

        return response()->json(['cart_count' => $totalCount]);
    }

    // Calculate the total cost for a specific item
    private function getItemTotal($cartItem)
    {
        $quantity = 0;
        if (Auth::check()) {
            $dl = new DataLayer();
            $cart = $dl->userCart(Auth::user()->id);
            $quantity = $cart->products()->where('product_id', $cartItem)->first()->pivot->quantity;
        } else {
            $quantity = $this->cart->getItemQuantity($cartItem);
        }

        // Retrieve the product price
        $product = Product::find($cartItem);
        $price = $product->price;

        // Get the discount percentage using DataLayer
        $dl = new DataLayer();
        $discountPercentage = $dl->getProductDiscount($cartItem);

        if ($discountPercentage > 0) {
            $price = $price - ($price * ($discountPercentage / 100));
        }

        return $price * $quantity;
    }

    // Calculate the total cost for the entire cart
    private function getTotalCartCost($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $this->getItemTotal($item);
        }
        return $total;
    }
}
