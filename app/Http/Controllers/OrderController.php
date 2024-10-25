<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function showConfirmation()
    {
        $cart = Cart::where('user_id', auth()->id())->get();
        $count = $cart->count(); // Ensure $count is passed to the view
        $categories = Category::all();
        return view('home.confirmation', compact('cart', 'count','categories'));
    }

    public function placeOrder(Request $request)
    {
        $order = new Order();
        $order->user_id = auth()->id();
        $order->total_amount = $request->total;
        $order->save();
    
        // Save order products
        foreach (Cart::where('user_id', auth()->id())->get() as $cartItem) {
            $order->products()->attach($cartItem->product_id, [
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price
            ]);
    
            // Decrement the product quantity
            $product = $cartItem->product;
            $product->quantity -= $cartItem->quantity;
            $product->save();
        }
    
        // Clear the user's cart
        Cart::where('user_id', auth()->id())->delete();
    
        return redirect()->route('order.thankyou', ['id' => $order->id]);
    }
    

    public function thankYou($order_id)
    {
        $order = Order::find($order_id);
        $categories = Category::all(); // Retrieve categories
        return view('home.thankyou', compact('order','categories'));
    }

    
}
