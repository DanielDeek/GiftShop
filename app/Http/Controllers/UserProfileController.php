<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        $categories = Category::all();
        return view('home.profile', compact('user', 'categories'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($user->name === $request->name && $user->email === $request->email) {
            return response()->json([
                'message' => 'No changes made.',
                'no_changes' => true,
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully!',
            'user' => $user,
            'no_changes' => false,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully!']);
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Perform logout if necessary, depending on your implementation
        Auth::logout();

        $user->delete();

        return response()->json(['message' => 'Account deleted successfully!']);
    }
}
