<?php

namespace App\Http\Controllers;
use App\Models\DataLayer;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() {
        $user = Auth::user();
        $dl = new DataLayer();
        $monthlySales = $dl->getYearlySalesData();
        $totalUsers = $dl->userCount();
        return view('admin.home')->with('user', $user)
                                 ->with('totalUsers', $totalUsers)
                                 ->with('monthlySales', $monthlySales);
    }

    public function products() {
        $dl = new DataLayer();
        $products = $dl->listProducts();
        $categories = $dl->listCategories();
        $subcategories = $dl->listSubcategories();
        $brands = $dl->listBrands();
        $search = null;
        
        $selectedBrand = '';
        $selectedCategory = '';
        $selectedSubcategory = '';

        return view('admin.product.products')->with('products',$products)
                                       ->with('categories',$categories)
                                       ->with('subcategories',$subcategories)
                                       ->with('brands',$brands)
                                       ->with('search',$search)
                                       ->with('selectedBrand',$selectedBrand)
                                       ->with('selectedCategory',$selectedCategory)
                                       ->with('selectedSubcategory',$selectedSubcategory);
    }

    public function filter(Request $request){

        $brandId = $request->input('brand');
        $categoryId = $request->input('category');
        $subcategoryId = $request->input('subcategory');
        $search = $request->input('search');

        $dl = new DataLayer();
        $brands = $dl->listBrands();
        $categories = $dl->listCategories();
        $subcategories = $dl->listSubcategories();
        $products = $dl->filteredProducts($brandId, $categoryId, $subcategoryId, $search);

        return view('admin.product.products')
            ->with('products', $products)
            ->with('categories', $categories)
            ->with('subcategories', $subcategories)
            ->with('brands', $brands)
            ->with('search', $search)
            ->with('selectedBrand', $brandId)
            ->with('selectedCategory', $categoryId)
            ->with('selectedSubcategory', $subcategoryId);
    }

    public function edit($id)
    {

        $dl = new DataLayer();
        $product = $dl->findProduct($id);
        $categories = $dl->listCategories();
        $subcategories = $dl->listSubcategories();
        

        return view('admin.product.edit', compact('product', 'categories', 'subcategories'));
    }

    public function editPromotion($id) {

        $dl = new DataLayer();
        $promotion = $dl->findPromotion($id);
        $productInPromotion = $dl->productInPromotion($promotion);
        $availableProducts = $dl->productsNotInPromotion();
        
        return view('admin.promotion.edit', compact('promotion', 'productInPromotion','availableProducts'));
    }

    public function update(Request $request, $id)
    {
        $dl = new DataLayer();
        $product = $dl->findProduct($id);


        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'brand' => 'required|string|max:255',
            'category' => 'required|exists:category,id',
            'subcategory' => 'required|exists:subcategory,id',
            'description' => 'required|string',
            'image' => 'nullable|url|max:2048',
        ]);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->subcategory_id = $request->subcategory;
        $product->image = $request->image;

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Prodotto aggiornato con successo');
    }

    public function updatePromotion(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'image' => 'nullable|url|max:2048',
        ]);

        $dl = new DataLayer();
        $promotion = $dl->findPromotion($id);

        $promotion->update($validatedData);

        $discounts = $request->input('discounts', []);

        $pivotData = [];
        foreach ($discounts as $productId => $discount) {
            $pivotData[$productId] = ['discount' => $discount];
        }

        $promotion->products()->sync($pivotData);
        
        $promotion->save();

        return redirect()->route('admin.promotions.index')->with('success', 'Promozione aggiornata con successo');
    }

    public function create()
    {
        $dl = new DataLayer();
        $categories = $dl->listCategories();
        $subcategories = $dl->listSubcategories();
        $brands = $dl->listBrands();

        return view('admin.product.create', compact('categories', 'subcategories','brands'));
    }

    public function createPromotion(){
        $dl = new DataLayer();
        $products = $dl->productsNotInPromotion();
        return view('admin.promotion.create', compact('products'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'brand' => 'required|exists:brand,id',
            'subcategory' => 'required|exists:subcategory,id',
            'description' => 'required|string',
            'image' => 'nullable|url|max:2048',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->subcategory_id = $request->subcategory;
        $product->brand_id = $request->brand;
        $product->release_date = $request->release_date;
        $product->image = $request->image;

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Prodotto creato con successo');
    }

    // ... other methods in AdminController.php ...

    public function storePromotion(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
            'product_ids' => 'required|array', // Ensure at least one product is selected
            'product_ids.*' => 'exists:product,id', // Validate each product ID
            'discounts' => 'required|array',
            'discounts.*' => 'numeric|min:0|max:1',
            'image' => 'nullable|url|max:2048',
        ]);

        // Create the promotion
        $promotion = Promotion::create($validatedData);

        // Attach products to the promotion with discounts
        foreach ($validatedData['product_ids'] as $productId) {
            $promotion->products()->attach($productId, [
                'discount' => $validatedData['discounts'][$productId] ?? 0,
            ]);
        }

        return redirect()->route('admin.promotions.index')->with('success', 'Promozione creata con successo');
    }

// ... other methods in AdminController.php ...


    public function promotions() {
        $dl = new DataLayer();
        $promotions = $dl->listPromotions();
        return view('admin.promotion.promotions')->with('promotions',$promotions);
    }

    public function orders() {
        $dl = new DataLayer();
        $orders = $dl->listOrders();
        return view('admin.order.orders', compact('orders'));
    }

    public function showOrder($id){
        $dl = new DataLayer();
        $order = $dl->findOrder($id);
        return view('admin.order.order', compact('order'));
    }


    public function destroy($id) {
        $dl = new DataLayer();
        $dl->deleteProduct($id);
        return redirect()->route('admin.products.index')->with('success', 'Prodotto eliminato con successo');
    }

    public function destroyPromotion($id) {
        $dl = new DataLayer();
        $dl->deletePromotion($id);
        return redirect()->route('admin.promotions.index')->with('success', 'Promozione eliminata con successo');
    }

    public function destroyOrder($id) {
        $dl = new DataLayer();
        $dl->deleteOrder($id);
        return redirect()->route('admin.orders.index')->with('success', 'Ordine eliminato con successo');
    }

    public function updateOrder(Request $request, $id){
        $dl = new DataLayer();
        $dl -> updateOrderState($id, $request->input('state'));

        return redirect()->route('admin.orders.index')->with('success', 'Ordine aggiornato con successo');
    }
    

}
