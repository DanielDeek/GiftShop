<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|file|max:2048', // Allow any file type up to 2MB
            'price' => 'required|numeric',
            'category' => 'required|integer|exists:categories,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Handle the image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $imagePath = $image->storeAs('images', $imageName, 'public'); // Store in 'public/images'
        }

        // Create the product
        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath, // Save the image path to the database
            'price' => $request->price,
            'category' => $request->category,
            'quantity' => $request->quantity,
        ]);

        // Render the new product row to append to the table
        $productRow = view('admin.product_row', ['product' => $product, 'categories' => Category::all()])->render();

        return response()->json(['msg' => 'Product added successfully!', 'row' => $productRow]);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'image' => 'nullable|file|max:2048', // Allow any file type up to 2MB
        'category' => 'required|integer|exists:categories,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($id);

    // Check if a new image is uploaded
    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Store the new image with a unique name
        $image = $request->file('image');
        $imageName = $image->getClientOriginalName(); // You might want to use a more unique name
        $imagePath = $image->storeAs('images', $imageName, 'public'); // Store in 'public/images' with original name

        // Update the product with the new image path
        $product->update([
            'image' => $imagePath,
        ]);
    }

    // Update the product details
    $product->update([
        'title' => $request->title,
        'description' => $request->description,
        'price' => $request->price,
        'category' => $request->category,
        'quantity' => $request->quantity,
    ]);

    return response()->json(['msg' => 'Product updated successfully!']);
}


    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            // Delete the image from storage
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['msg' => 'Product deleted successfully!']);
    }
}
