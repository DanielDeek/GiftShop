<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminSearchController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminProfileController;

// Public routes
Route::get('/', function () {
    return view('home.home');
});
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [ContactController::class, 'showContactForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit']);
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/categories/{id}', [HomeController::class, 'showCategory'])->name('category.show');
Route::get('/categories', [HomeController::class, 'showCategories'])->name('categories');
Route::get('category/{id}/products', [HomeController::class, 'categoryProducts'])->name('category.products');
Route::get('/product', [HomeController::class, 'allProducts']);
Route::get('/product_details/{id}', [HomeController::class, 'product_details'])->name('product.details');
// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('add_cart/{id}', [CartController::class, 'add_cart'])->name('cart.add');
    Route::get('mycart', [CartController::class, 'mycart'])->name('cart.view');
    Route::get('delete_cart/{id}', [CartController::class, 'delete_cart'])->name('cart.delete');
    Route::get('/confirm_order', [OrderController::class, 'showConfirmation'])->name('order.confirmation');
    Route::post('/place_order', [OrderController::class, 'placeOrder'])->name('order.place');
    Route::get('/thankyou/{id}', [OrderController::class, 'thankYou'])->name('order.thankyou');
    Route::get('/profile', [UserProfileController::class, 'showProfile'])->name('profile.show');
    Route::post('/profile/update', [UserProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password/update', [UserProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile/delete', [UserProfileController::class, 'deleteAccount'])->name('profile.delete');
});

// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'index']);
    Route::get('/admin/categories', [AdminController::class, 'index'])->name('admin.categories');
    Route::post('/admin/categories/add', [AdminController::class, 'store'])->name('admin.categories.store');
    Route::post('/admin/categories/update/{id}', [AdminController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/delete/{id}', [AdminController::class, 'destroy'])->name('admin.categories.destroy');
    // Route for displaying the manage items page
    Route::prefix('admin')->group(function () {
        Route::get('/profile', [AdminProfileController::class, 'showProfile'])->name('admin.profile.show');
        Route::post('/profile', [AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');
        Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profile.password.update');
        Route::delete('/profile', [AdminProfileController::class, 'deleteAccount'])->name('admin.profile.delete');
        Route::get('products', [AdminProductController::class, 'index'])->name('admin.products.index');
        Route::post('products', [AdminProductController::class, 'store'])->name('admin.products.store');
        Route::post('products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
        Route::delete('products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::get('/products/search', [AdminSearchController::class, 'productSearch'])->name('admin.products.search');
        Route::post('/products/search/{id}', [AdminSearchController::class, 'updateProduct'])->name('admin.products.search.update');
        Route::delete('/products/{id}', [AdminSearchController::class, 'deleteProduct'])->name('admin.products.destroy');
    });



    Route::get('order', [AdminController::class, 'view_order'])->name('order');
    Route::post('change_status/{id}', [AdminController::class, 'change_status'])->name('admin.change_status');
    Route::post('on_the_way/{id}', [AdminController::class, 'on_the_way'])->name('admin.on_the_way');
    Route::post('delivered/{id}', [AdminController::class, 'delivered'])->name('admin.delivered');
    Route::delete('delete_order/{id}', [AdminController::class, 'delete_order'])->name('admin.delete_order');
});


// Auth routes
require __DIR__ . '/auth.php';
