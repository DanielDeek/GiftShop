<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Perform the product search
        $productResults = Product::where('title', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();

        // Perform the category search
        $categoryResults = Category::where('category_name', 'like', '%' . $query . '%')->get();

        // Get all products under the matching categories
        $categoryProductResults = collect(); // Initialize an empty collection
        foreach ($categoryResults as $category) {
            $productsInCategory = $category->products; // Assuming the Category model has a 'products' relationship
            $categoryProductResults = $categoryProductResults->merge($productsInCategory);
        }

        // Retrieve all categories for display in the view (e.g., for a sidebar)
        $categories = Category::all();

        // Get cart count
        $count = Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0;

        // Return the view with results
        return view('home.search', [
            'productResults' => $productResults,
            'categoryProductResults' => $categoryProductResults,
            'query' => $query,
            'categories' => $categories,
            'count' => $count,
        ]);
    }
}