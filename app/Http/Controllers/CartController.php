<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SessionCart;
use App\Models\DataLayer;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Auth;



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

        return response()->json(['cart_count' => $this->cart->count()]); 
    }

    public function remove($productId) {

        $this->cart->remove($productId);

        return redirect()->route('cart')->with('success', 'Prodotto rimosso dal carrello!');
    }

    public function update(Request $request, $productId)
    {
        $this->cart->update($productId, $request->input('quantity'));

        return redirect()->route('cart')->with('success', 'Quantità aggiornata!');
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

        return redirect()->route('cart')->with('success', 'Carrello svuotato!');
    }

    public function count(){
        $cartCount = $this->cart->count();
        return response()->json(['cart_count' => $cartCount]);
    }

    public function getTotalCartCount()
    {
        $totalCount = 0;

        // 1. Get Session Cart Count
        $totalCount += $this->cart->count(); // Assuming your SessionCart has a count() method

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
}
