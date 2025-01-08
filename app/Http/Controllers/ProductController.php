<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataLayer;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;



class ProductController extends Controller
{
    public function index(){
        $dl = new DataLayer();
        $products = $dl->listProducts();
        $categories = $dl->listCategories();
        $subcategories = $dl->listSubcategories();
        $brands = $dl->listBrands();
        $search = null;
        
        $selectedBrand = '';
        $selectedCategory = '';
        $selectedSubcategory = '';

        return view('product.products')->with('products',$products)
                                       ->with('categories',$categories)
                                       ->with('subcategories',$subcategories)
                                       ->with('brands',$brands)
                                       ->with('search',$search)
                                       ->with('selectedBrand',$selectedBrand)
                                       ->with('selectedCategory',$selectedCategory)
                                       ->with('selectedSubcategory',$selectedSubcategory);
    }

    public function show($id){
        $dl = new DataLayer();
        $product = $dl->findProduct($id);
        $categories = $dl->listCategories();
        if ($product) {
            $reviews = $dl->productReviews($id);
            $similarProducts = $dl->productsFromSubcategory($product->subcategory_id);
            return view('product.product')->with('product', $product)
                                          ->with('reviews', $reviews)
                                          ->with('similarProducts', $similarProducts)
                                          ->with('categories', $categories);
        } else {
            // Handle the case where the product is not found
            return view('errors.404'); // Or redirect to a different page
        }
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

        return view('product.products')
            ->with('products', $products)
            ->with('categories', $categories)
            ->with('subcategories', $subcategories)
            ->with('brands', $brands)
            ->with('search', $search)
            ->with('selectedBrand', $brandId)
            ->with('selectedCategory', $categoryId)
            ->with('selectedSubcategory', $subcategoryId);
    }

    public function userReviews(){
        $dl = new DataLayer();
        $categories = $dl->listCategories();
        $userReviews = $dl->userReviews();
        return view('user.reviews', compact('categories', 'userReviews'));
    }

    public function reviewCreate($id) {

        $dl = new DataLayer();
        $categories = $dl->listCategories();
        $product = $dl->findProduct($id);

        return view('user.reviewCreate', compact('product','categories'));
    }

    public function reviewStore(Request $request, $id) {
        $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'stars' => 'required|integer|between:1,5', 
        ]);
    
        $dl = new DataLayer();
        $dl->addReview($id, Auth::user()->id,
                       $request->input('title'),
                       $request->input('text'), 
                       $request->input('stars'));
    
        return redirect()->route('product.show', $id)->with('success', 'Recensione aggiunta con successo!');
    }

    

}

