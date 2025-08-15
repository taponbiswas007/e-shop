<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Add product to cart
    public function add(Request $request, $productId)
    {
        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ],
            [
                'quantity' => DB::raw('quantity + 1')
            ]
        );

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // Get cart items
    public function index()
    {
        if (Auth::check()) {
            $carts = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $sessionId = Session::getId();
            $carts = Cart::with('product')
                ->where('session_id', $sessionId)
                ->get();
        }

        return Inertia::render('Cart/Index', [
            'carts' => $carts
        ]);
    }

    // Remove item
    public function remove(Request $request, $id)
    {
        $sessionId = $request->session()->getId();
        $userId = Auth::id();

        Cart::where('id', $id)
            ->where(function ($q) use ($userId, $sessionId) {
                if ($userId) $q->where('user_id', $userId);
                else $q->where('session_id', $sessionId);
            })->delete();

        return response()->json(['message' => 'Item removed']);
    }

    // Clear cart (checkout)
    public function clear(Request $request)
    {
        $sessionId = $request->session()->getId();
        $userId = Auth::id();

        Cart::where(function ($q) use ($userId, $sessionId) {
            if ($userId) $q->where('user_id', $userId);
            else $q->where('session_id', $sessionId);
        })->delete();

        return response()->json(['message' => 'Cart cleared']);
    }
}
