<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        // $products = Product::with('category')
        //     ->latest()
        //     ->paginate(10)
        //     ->through(function ($product) {
        //         // Build image URLs with null checks
        //         $imageUrls = [
        //             'main_image_url' => $product->main_image ? asset('storage/' . $product->main_image) : null,
        //         ];

        //         // Add additional images (1-5)
        //         for ($i = 1; $i <= 5; $i++) {
        //             $imageField = "image_$i";
        //             $imageUrls["image_{$i}_url"] = $product->$imageField ? asset('storage/' . $product->$imageField) : null;
        //         }

        //         return [
        //             'id' => $product->id,
        //             'name' => $product->name,
        //             'category' => $product->category->name,
        //             'short_description' => $product->short_description,
        //             'description' => $product->description,
        //             'price' => $product->price,
        //             'discount' => $product->discount,
        //             'net_price' => $product->net_price,
        //             'unit' => $product->unit,
        //             'status' => $product->status,
        //             'video_url' => $product->video_url,
        //             'created_at' => $product->created_at->format('d M Y'),
        //             'created_at_full' => $product->created_at->toDateTimeString(),
        //         ] + $imageUrls;
        //     });

        // return Inertia::render('Products/Index', [
        //     'products' => $products,
        //     'status' => session('success'),
        // ]);
        $products = Product::latest()->get();
        return Inertia::render('Products/Index', compact('products'));
    }

    public function create()
    {
        return Inertia::render('Products/Create', [
            'categories' => Category::where('status', true)
                ->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'unit' => 'required|string|max:50',
            'status' => 'boolean'
        ]);

        // Calculate net price
        $validated['net_price'] = $validated['price'] - ($validated['price'] * ($validated['discount'] / 100));

        // Handle file uploads
        $validated['main_image'] = $request->file('main_image')->store('products', 'public');

        foreach (range(1, 5) as $i) {
            if ($request->hasFile("image_$i")) {
                $validated["image_$i"] = $request->file("image_$i")->store('products', 'public');
            }
        }

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'main_image' => asset('storage/' . $product->main_image),
                'images' => collect(range(1, 5))
                    ->map(function ($i) use ($product) {
                        return $product["image_$i"]
                            ? asset('storage/' . $product["image_$i"])
                            : null;
                    })
                    ->filter()
                    ->values()
                    ->toArray(),
                'video_url' => $product->video_url,
                'price' => $product->price,
                'discount' => $product->discount,
                'net_price' => $product->net_price,
                'unit' => $product->unit,
                'status' => $product->status,
                'created_at' => $product->created_at->format('d M Y'),
            ]
        ]);
    }

    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => Category::all() // or whatever query you use
        ]);
    }

    public function update(Request $request, Product $product)
    {
        // Validate
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'net_price' => 'required|numeric',
            'unit' => 'nullable|string|max:50',
            'status' => 'required|boolean',
            'video_url' => 'nullable|string|max:255',
            'main_image' => 'nullable|image|max:2048',
            'image_1' => 'nullable|image|max:2048',
            'image_2' => 'nullable|image|max:2048',
            'image_3' => 'nullable|image|max:2048',
            'image_4' => 'nullable|image|max:2048',
            'image_5' => 'nullable|image|max:2048',
        ]);

        // Images handling
        for ($i = 0; $i <= 5; $i++) {
            $field = $i === 0 ? 'main_image' : "image_$i";

            if ($request->hasFile($field)) {
                // Delete old image if exists
                if ($product->$field) {
                    Storage::disk('public')->delete($product->$field);
                }

                // Store new image
                $data[$field] = $request->file($field)->store('products', 'public');
            }
        }

        // Update product
        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }






    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
