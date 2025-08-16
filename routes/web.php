<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialLoginController;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{name}', [CategoryController::class, 'show'])->name('category.show');


// social login


Route::get('/auth/google', [SocialLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/auth/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/orders', fn() => Inertia::render('Orders/Index'))->name('orders.index');
    Route::get('/customers', fn() => Inertia::render('Customers/Index'))->name('customers.index');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
});

// User routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/welcome', [HomeController::class, 'index'])->name('welcome');

    // Cart Routes
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
});

// Profile (any authenticated user)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
