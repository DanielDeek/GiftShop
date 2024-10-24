<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add_cart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if the product exists
        $product = Product::findOrFail($id);

        // Check if the requested quantity is available
        if ($product->quantity < $request->input('quantity')) {
            return response()->json(['error' => 'Insufficient stock available.'], 400);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'You need to be logged in to add items to the cart.'], 401);
        }

        // Add the product to the cart
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->where('product_id', $product->id)->first();

        if ($cart) {
            // Update the quantity if the product is already in the cart
            $cart->quantity += $request->input('quantity');
            $cart->save();
        } else {
            // Create a new cart entry if the product is not in the cart
            Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'quantity' => $request->input('quantity'),
            ]);
        }

        // Decrement the product quantity
        $product->quantity -= $request->input('quantity');
        $product->save();

        // Fetch all categories to pass to the view
        $categories = Category::all();

        // Redirect to the My Cart view with categories
        return redirect()->back()->with('categories', $categories);
    }

    public function mycart()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to view your cart.');
        }

        // Fetch user's cart items
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->with('product')->get(); // Include product details
        $count = $cart->count();

        // Fetch all categories to pass to the view
        $categories = Category::all();

        return view('home.mycart', compact('count', 'cart', 'categories'));
    }

    public function delete_cart($id)
    {
        // Attempt to find the cart item by ID
        $cart = Cart::findOrFail($id);

        // Retrieve the associated product
        $product = $cart->product;

        // If the quantity in the cart is greater than 1, decrement the quantity by 1
        if ($cart->quantity > 1) {
            $cart->quantity -= 1;
            $cart->save();
        } else {
            // If the quantity is 1 or less, remove the item from the cart
            $cart->delete();
        }

        // Increment the product quantity
        $product->quantity += 1;
        $product->save();

        return redirect()->back()->with('success', 'Product removed from the cart successfully.');
    }
}
