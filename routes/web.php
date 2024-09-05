<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\promotionController;
use App\Models\Product;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\CheckoutController;
use Hamcrest\Core\Set;
use App\Http\Middleware\SetLocale;

Route::middleware(SetLocale::class)->group(function () {

    Route::get('/', function () {
        return redirect()->route('home');
    });

    Route::resource('product',ProductController::class);
    Route::get('/products/filter', [ProductController::class, 'filter'])->name('product.filter');


    Route::resource('promotion',promotionController::class);

    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::get('contact', [HomeController::class, 'index'])->name('contact');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    //Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    Route::get('/cart/total-count', [CartController::class, 'getTotalCartCount'])->name('cart.total-count');



    Route::get('account', [HomeController::class, 'index'])->name('account');
    Route::get('wishlist', [HomeController::class, 'index'])->name('wishlist');

    Route::get('category', [HomeController::class, 'index'])->name('category');
    Route::get('categories', [HomeController::class, 'index'])->name('categories');

    Route::get('blog.show', [HomeController::class, 'index'])->name('blog.show');

    Route::get('newsletter.signup', [HomeController::class, 'index'])->name('newsletter.signup');



    Route::middleware('auth')->group(function () {

        Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
        Route::get('/profile/wishlist', [ProfileController::class, 'wishlist'])->name('profile.wishlist');
        Route::post('/profile/wishlist/add', [ProfileController::class, 'addToWishlist'])->name('profile.wishlist.add');
        Route::delete('/profile/wishlist/remove/{product}', [ProfileController::class, 'removeFromWishlist'])->name('profile.wishlist.remove');
        Route::post('/profile/wishlist/clear', [ProfileController::class, 'clearToWishlist'])->name('profile.wishlist.clear');
        Route::get('/profile/wishlist/count', [ProfileController::class, 'countToWishlist'])->name('profile.wishlist.count');

        
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [CheckoutController::class, 'processPayment'])->name('checkout.process');

        Route::get('/profile/reviews', [ProductController::class, 'userReviews'])->name('profile.reviews');
        Route::get('/product/{product}/review/create', [ProductController::class, 'reviewCreate'])->name('product.review.create');
        Route::post('/product/{product}/review/store', [ProductController::class, 'reviewStore'])->name('product.review.store');
        // Rotte per admin

    });

    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/admin/home', [AdminController::class, 'index'])->name('admin.home');

        // Route for the products
        Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products.index');
        Route::get('/admin/products/create', [AdminController::class, 'create'])->name('admin.product.create');
        Route::post('/admin/products/store', [AdminController::class, 'store'])->name('admin.product.store');
        Route::get('/admin/products/edit/{product}', [AdminController::class, 'edit'])->name('admin.product.edit');
        Route::put('/admin/products/update/{product}', [AdminController::class, 'update'])->name('admin.product.update');
        Route::delete('/admin/products/delete/{product}', [AdminController::class, 'destroy'])->name('admin.product.destroy');
        Route::get('/admin/products/filter', [AdminController::class, 'filter'])->name('admin.products.filter');

        // Route for the promotions
        Route::get('admin/promotions', [AdminController::class, 'promotions'])->name('admin.promotions.index');
        Route::delete('admin/promotion/delete/{promotion}', [AdminController::class, 'destroyPromotion'])->name('admin.promotion.destroy');
        Route::get('admin/promotion/edit/{promotion}', [AdminController::class, 'editPromotion'])->name('admin.promotion.edit');
        Route::put('admin/promotion/update/{promotion}', [AdminController::class, 'updatePromotion'])->name('admin.promotion.update');
        Route::get('admin/promotion/create', [AdminController::class, 'createPromotion'])->name('admin.promotion.create');
        Route::post('admin/promotion/store', [AdminController::class, 'storePromotion'])->name('admin.promotion.store');
        
        // Route for the orders
        Route::get('admin/orders', [AdminController::class, 'orders'])->name('admin.orders.index');
        Route::get('admin/orders/edit/{order}', [AdminController::class, 'edit'])->name('admin.order.edit');
        Route::get('admin/orders/show/{order}', [AdminController::class, 'showOrder'])->name('admin.order.show');
        Route::delete('admin/orders/delete/{order}', [AdminController::class, 'destroyOrder'])->name('admin.order.destroy');
        Route::put('admin/orders/update/{order}', [AdminController::class, 'updateOrder'])->name('admin.order.update');
        //)
    });


    Route::get('locale/{locale}', function ($locale) {
        if (in_array($locale, config('app.available_locales'))) {
            session(['locale' => $locale]);
        }
        return redirect()->back()->withHeaders(['Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0']); 
    })->name('locale.switch');


    require __DIR__.'/auth.php';

});