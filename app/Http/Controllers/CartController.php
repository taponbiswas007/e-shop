<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CartController extends Controller
{
    // Add product to cart
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => DB::raw('1')
            ]
        );

        return redirect()->back()->with('success', 'Product added to cart!');
    }


    // Get cart items
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $carts = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Cart/Index', [
            'carts' => $carts
        ]);
    }

    // Remove item
    public function remove($id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json(['message' => 'Item removed']);
    }

    // Clear cart (checkout)
    public function clear()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Cart::where('user_id', Auth::id())->delete();

        return response()->json(['message' => 'Cart cleared']);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.1'
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->update([
            'quantity' => $request->quantity
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->delete();

        return response()->json(['success' => true]);
    }
}
