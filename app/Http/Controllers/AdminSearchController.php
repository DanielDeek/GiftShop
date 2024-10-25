<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSearchController extends Controller
{
    public function productSearch(Request $request)
    {
        $search = $request->input('q');
        $products = Product::where('title', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhere('category', 'like', '%' . $search . '%')
            ->paginate(4); // Adjust the pagination as per your requirement
        $categories = Category::all(); // Example of retrieving categories
        return view('admin.search_results', compact('products', 'search','categories'));
    }
    public function updateProduct(Request $request, $id)
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

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
