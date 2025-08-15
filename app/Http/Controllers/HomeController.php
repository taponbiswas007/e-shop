<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function ($q) {
            $q->latest()->take(4); // হোমপেজে ৪টি প্রোডাক্ট দেখাবে
        }])->get();

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
