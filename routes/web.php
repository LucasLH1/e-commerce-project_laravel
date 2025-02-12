<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AddressController;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Testimonial;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');


//Route::middleware([
//    'auth:sanctum',
//    config('jetstream.auth_session'),
//    'verified',
//])->group(function () {
//    Route::get('/dashboard', function () {
//        return view('dashboard');
//    })->name('dashboard');
//});

Route::get('/search', function (Request $request) {
    $query = $request->input('q');

    // Recherche dans les noms des produits
    $products = Product::where('name', 'LIKE', "%{$query}%")->limit(10)->get();

    return response()->json($products);
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

// Routes accessibles uniquement aux utilisateurs connectés sans roles
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::get('/checkout/quick-buy/{product}', [CheckoutController::class, 'quickBuy'])->name('checkout.quickBuy');
    Route::post('/checkout/process', [CheckoutController::class, 'processPayment'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');


    // Routes pour la gestion des adresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses/store', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/edit/{id}', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/update/{id}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/delete/{id}', [AddressController::class, 'destroy'])->name('addresses.delete');
    Route::post('/addresses/set-active/{id}', [AddressController::class, 'setActive'])->name('addresses.setActive');

    Route::get('/orders', [OrderController::class, 'history'])->name('orders.history');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');

    Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

});

require __DIR__.'/admin.php';
