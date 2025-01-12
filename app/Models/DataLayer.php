<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;





class DataLayer
{
    public function listProducts(){
        $products = Product::orderBy('price','desc')->with('promotion')->paginate(12);
        return $products;
    }
    
    public function findProduct($id){
        $product = Product::findOrFail($id);
        return $product;
    }

    public function deleteProduct($id) {
        $product = Product::findOrFail($id);
        $product->delete();
    }


    public function filteredProducts($brandId = null, $categoryId = null, $subcategoryId = null, $search = null) {
        // Inizializza la query su Product
        $products = Product::query();
    
        // Se c'è un termine di ricerca, applica la ricerca
        if ($search) {
            $products->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
    
        // Filtra per marca
        if ($brandId) {
            $products->where('brand_id', $brandId);
        }
    
        // Filtra per sottocategoria
        if ($subcategoryId) {
            $products->where('subcategory_id', $subcategoryId);
        }
    
        // Filtra per categoria (attraverso la relazione con subcategory)
        if ($categoryId) {
            $products->whereHas('subcategory', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }
    
        // Ritorna i prodotti filtrati
        return $products->paginate(12);
    }
    

    public function search($search){
    
        // Sanitize the search query (optional)
        $sanitizedsearch = $this->sanitizeInput($search); // Replace with your sanitization logic
    
        // Perform the search using Eloquent
        return Product::where('name', 'LIKE', "%{$sanitizedsearch}%")
                            ->orWhere('description', 'LIKE', "%{$sanitizedsearch}%");
    }



    public function listCategories(){
        $categories = Category::all();
        return $categories;
    }  

    public function activePromotions(){
        $promotions = Promotion::where('start', '<=', now())
                                ->where('end', '>=', now())
                                ->get();
        return $promotions;
    }
    
    public function listSubcategories(){
        $subcategories = Subcategory::all();
        return $subcategories;
    }

    public function findSubcategory($id){
        $subcategory = Subcategory::findOrFail($id);
        return $subcategory;
    }

    public function listBrands(){
        $brands = Brand::all();
        return $brands;
    }

    public function findBrand($id){
        $brand = Brand::findOrFail($id);
        return $brand;
    }

    public function listPromotions(){
        $promotions = Promotion::orderBy('start','desc')->get();
        return $promotions;
    }

    public function findPromotion($id){
        $promotion = Promotion::findOrFail($id);
        return $promotion;
    }


    public function productInPromotion($promotion) {
        $products = $promotion->products()
                              ->get();
        return $products;
    }

    public function productsNotInPromotion() {
        return Product::whereDoesntHave('promotion')->get();
    }

    public function deletePromotion($id) {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
    }

    public function getProductDiscount($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return 0;
        }
        
        $activePromotion = $product->promotion()
                                  ->where('start', '<=', now())
                                  ->where('end', '>=', now())
                                  ->first();

        if ($activePromotion) {
            return $activePromotion->pivot->discount; 

        return 0;
        }
    }


    public function productReviews($id){
        // $product = Product::findOrFail($id);
        // $reviews = $product->reviews()->orderBy('created_at', 'desc')->paginate(5);
        // return $reviews;

        $reviews = Review::where('product_id', $id)->orderBy('created_at', 'desc')->paginate(5);
        return $reviews;
    }

    public function productsFromSubcategory($subcategory_id) {
        return Product::where('subcategory_id', $subcategory_id)
            ->orderBy('release_date', 'desc')
            ->get()
            ->take(5);
    }

    public function bestProducts() {
        return Product::with('reviews')
            ->orderByDesc(DB::raw('(SELECT AVG(stars) FROM review WHERE review.product_id = product.id)'))
            ->take(10) // Limit to 10 best products
            ->get();
    }

    public function latestProducts() {
        return Product::orderBy('release_date', 'desc')
            ->take(10)
            ->get();
    }

    private function sanitizeInput($input){
        // Remove potentially harmful characters
        $sanitizedInput = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        // Trim whitespace
        $sanitizedInput = trim($sanitizedInput);

        // Convert to lowercase (optional)
        $sanitizedInput = strtolower($sanitizedInput);

        return $sanitizedInput;
    }

    public function userOrders($userId){
        return Order::where('user_id', $userId)->get();
    }

    public function createUserWishlist($userId){
        return Wishlist::create([
            'user_id' => $userId,
            'total' => 0,
        ]);
    }

    public function userWishlist($userId) {
        return Wishlist::where('user_id', $userId)->with('products')->first();
    }

    public function userCart($userId) {
        return Cart::where('user_id', $userId)->with('products')->first();
    }

    public function userWishlistProducts($userId){
        return Wishlist::where('user_id', $userId)->with('products')->get();
    }

    public function addToWishlist($userId, $productId) {

        $wishlist = Wishlist::where('user_id', $userId)->first();

        if (!$wishlist) {
            $wishlist = new Wishlist();
            $wishlist->user_id = $userId;
            $wishlist->total = 0;
            $wishlist->save();
        }

        // Check if the product is already in the wishlist
        if ($wishlist->products()->where('product_id', $productId)->exists()) {
            return false; // Or throw an exception if you want to handle it differently
        }

        $product = $this->findProduct($productId);

        if ($product) {
            $wishlist->products()->attach($productId);
            $wishlist->total += $product->price; // Add product price to total
            $wishlist->save(); 
            return true;
        } else {
            // Handle the case where the product doesn't exist
            return false; 
        }
    }

    public function removeFromWishlist($userId, $productId) {
        $wishlist = Wishlist::where('user_id', $userId)->first();

        if ($wishlist) {
            // detach() removes the association from the pivot table
            $wishlist->products()->detach($productId); 

            // Update the total price (optional but recommended)
            $wishlist->total = $wishlist->products()->sum('price');
            $wishlist->save();

            return true;
        }

        return false; 
    }

    public function deleteOrder($id) {
        $order = Order::findOrFail($id);
        $order->delete();
    }

    public function cartProducts($cartId){
        return Cart::findOrFail($cartId)->products()->with('promotion')->get();  
    }

    public function listOrders(){
        $orders = Order::orderBy('date', 'desc')->get();
        return $orders;
    }

    public function findOrder($id){
        $order = Order::findOrFail($id);
        return $order;
    }

    public function updateOrderState($id, $state){
        $order = Order::findOrFail($id);
        $order->state = $state;
        $order->save();
    }

    public function userReviews(){
        $userId = Auth::user()->id;
        $reviews = Review::where('user_id', $userId)->get();
        return $reviews;
    }

    public function addReview($productId, $userId, $title, $text, $stars) {
        
        $review = new Review();
        $review->product_id = $productId;
        $review->user_id = $userId;
        $review->title = $title;
        $review->text = $text;
        $review->stars = $stars;
        $review->created_at = now();
        $review->save();
    }

    public function userCount(){
        return User::count();
    }

    public function getMonthlySalesData(int $year = null): array
    {
        $query = Order::selectRaw('MONTH(date) as month, SUM(total) as total_sales')
            ->groupBy('month');

        if ($year) {
            $query->whereYear('date', $year);
        } else {
            $query->whereYear('date', Carbon::now()->year);
        }

        return $query->get()->toArray();
    }

    public function getYearlySalesData(): array
    {
        return Order::selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(total) as total_sales')
            ->where('date', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->toArray();
    }
}


