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
        $products = Product::with('category')
            ->latest()
            ->paginate(10)
            ->through(function ($product) {
                // Build image URLs with null checks
                $imageUrls = [
                    'main_image_url' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                ];

                // Add additional images (1-5)
                for ($i = 1; $i <= 5; $i++) {
                    $imageField = "image_$i";
                    $imageUrls["image_{$i}_url"] = $product->$imageField ? asset('storage/' . $product->$imageField) : null;
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name,
                    'short_description' => $product->short_description,
                    'description' => $product->description,
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'net_price' => $product->net_price,
                    'unit' => $product->unit,
                    'status' => $product->status,
                    'video_url' => $product->video_url,
                    'created_at' => $product->created_at->format('d M Y'),
                    'created_at_full' => $product->created_at->toDateTimeString(),
                ] + $imageUrls;
            });

        return Inertia::render('Products/Index', [
            'products' => $products,
            'status' => session('success'),
        ]);
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
        // Build image URLs array
        $imageUrls = [
            'main_image_url' => $product->main_image ? asset('storage/' . $product->main_image) : null,
        ];

        // Add additional images (1-5)
        for ($i = 1; $i <= 5; $i++) {
            $imageField = "image_$i";
            $imageUrls["image_{$i}_url"] = $product->$imageField ? asset('storage/' . $product->$imageField) : null;
        }

        return Inertia::render('Products/Edit', [
            'product' => array_merge($product->toArray(), [
                'category' => $product->category->name,
                'created_at_formatted' => $product->created_at->format('M d, Y'),
            ], $imageUrls),
            'categories' => Category::where('status', true)
                ->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $rules = [
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'discount' => 'sometimes|nullable|numeric|min:0|max:100',
            'net_price' => 'sometimes|nullable|numeric|min:0',
            'short_description' => 'sometimes|nullable|string',
            'description' => 'sometimes|nullable|string',
            'main_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_main_image' => 'sometimes|boolean',
            'video_url' => 'sometimes|nullable|url',
            'status' => 'sometimes|required|boolean',
            'unit' => 'sometimes|nullable|string|max:20',
        ];

        for ($i = 1; $i <= 5; $i++) {
            $rules["image_$i"] = 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules["remove_image_$i"] = 'sometimes|boolean';
        }

        $validatedData = $request->validate($rules);

        // Handle main image
        if ($request->hasFile('main_image')) {
            $this->deleteImage($product->main_image);
            $validatedData['main_image'] = $this->uploadImage($request->file('main_image'));
        } elseif ($request->remove_main_image) {
            $this->deleteImage($product->main_image);
            $validatedData['main_image'] = null;
        }

        // Handle additional images
        for ($i = 1; $i <= 5; $i++) {
            if ($request->hasFile("image_$i")) {
                $this->deleteImage($product->{"image_$i"});
                $validatedData["image_$i"] = $this->uploadImage($request->file("image_$i"));
            } elseif ($request->{"remove_image_$i"}) {
                $this->deleteImage($product->{"image_$i"});
                $validatedData["image_$i"] = null;
            }
        }

        // Calculate net price if price/discount changed
        if (isset($validatedData['price']) || isset($validatedData['discount'])) {
            $price = $validatedData['price'] ?? $product->price;
            $discount = $validatedData['discount'] ?? $product->discount;
            $validatedData['net_price'] = $price - ($price * ($discount / 100));
        }

        $product->update(array_filter($validatedData));

        return back()->with('success', 'Product updated successfully!');
    }

    private function uploadImage($file)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->extension();
        $file->storeAs('products', $filename, 'public');
        return 'storage/products/' . $filename;
    }

    private function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists(str_replace('storage/', '', $path))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $path));
        }
    }





    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
