<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch all categories
        $categories = Category::all();

        // Fetch latest products
        $latestProducts = Product::latest()->take(4)->get();

        // Count cart items if user is authenticated
        $count = Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0;

        return view('home.home', compact('categories', 'latestProducts', 'count'));
    }

    public function categoryProducts($id)
    {
        $category = Category::find($id);

        // Check if the category was found
        if (!$category) {
            // Handle the case where the category is not found
            return redirect()->route('home.index')->with('error', 'Category not found.');
        }

        // Fetch the products related to the specific category
        $products = Product::where('category', $id)->get();

        // Fetch all categories to populate the dropdown menu
        $categories = Category::all();

        // Count the cart items if the user is authenticated
        $count = Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0;

        // Return the view with the category, products, categories, and cart item count
        return view('home.category_products', compact('category', 'products', 'categories', 'count'));
    }

    public function allProducts()
    {
        // Fetch all products
        $allProducts = Product::all();

        // Count cart items if user is authenticated
        $count = Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0;

        // Return the view with all products and cart item count
        return view('home.all_products', compact('allProducts', 'count'));
    }

    public function product_details($id)
    {
        $product = Product::findOrFail($id);
        $count = Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0;
        $categories = Category::all(); // Fetch all categories
        $category = Category::find($product->category); // Fetch the category associated with the product

        return view('home.product_details', compact('product', 'count', 'categories', 'category'));
    }

    
}
