<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Contact;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:contacts,email', // Unique email validation
            'phone' => 'required|string|max:15',
            'message' => 'required|string',
        ]);

        // Create a new contact record
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);

        // Flash message using toastr (ensure toastr is set up in your project)
        toastr()->success('Your message has been sent successfully!');

        return redirect()->back();
    }

    public function showContactForm()
    {
        $count = Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0;
        $categories = Category::all();

        return view('home.contact', compact('count', 'categories'));
    }
}
