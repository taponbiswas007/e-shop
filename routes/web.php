<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Add these new routes
    Route::get('/products', function () {
        return Inertia::render('Products/Index');
    })->name('products.index');

    Route::get('/orders', function () {
        return Inertia::render('Orders/Index');
    })->name('orders.index');

    Route::get('/customers', function () {
        return Inertia::render('Customers/Index');
    })->name('customers.index');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/welcome', function () {
        return Inertia::render('Welcome');
    })->name('welcome');

    // এখানে শুধু সাধারণ user এর জন্য route গুলো রাখো
});










Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
// Route::middleware('auth')->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');

//     // Add these new routes
//     Route::get('/products', function () {
//         return Inertia::render('Products/Index');
//     })->name('products.index');

//     Route::get('/orders', function () {
//         return Inertia::render('Orders/Index');
//     })->name('orders.index');

//     Route::get('/customers', function () {
//         return Inertia::render('Customers/Index');
//     })->name('customers.index');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
