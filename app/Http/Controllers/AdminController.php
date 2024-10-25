<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255|unique:categories,category_name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = new Category();
        $category->category_name = $request->category;
        $category->save();

        // Render the new row for the category list
        $row = View::make('admin.category_row', ['c' => $category])->render();

        return response()->json([
            'row' => $row,
            'msg' => 'Category added successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($request->category === $category->category_name) {
            return response()->json([
                'error' => 'No changes made',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255|unique:categories,category_name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->category_name = $request->category;
        $category->save();

        return response()->json([
            'msg' => 'Category updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'msg' => 'Category deleted successfully'
        ]);
    }

    public function products()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function add_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'txtName_0' => 'required|string|max:255',
            'txtDescription_0' => 'required|string',
            'txtPrice_0' => 'required|numeric',
            'txtQuantity_0' => 'required|integer',
            'txtImage_0' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'cmbCategories_0' => 'required|integer|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->title = $request->input('txtName_0');
        $product->description = $request->input('txtDescription_0');
        $product->price = $request->input('txtPrice_0');
        $product->quantity = $request->input('txtQuantity_0');
        $product->category = $request->input('cmbCategories_0');

        if ($request->hasFile('txtImage_0')) {
            $image = $request->file('txtImage_0');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        $newProductRow = View::make('admin.partials.product_row', compact('product'))->render();

        return response()->json(['msg' => 'Product added successfully!', 'row' => $newProductRow]);
    }

    public function update_product(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'txtName_' . $id => 'required|string|max:255',
            'txtDescription_' . $id => 'required|string',
            'txtPrice_' . $id => 'required|numeric',
            'txtStock_' . $id => 'required|integer',
            'txtPhoto_' . $id => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'cmbCategories_' . $id => 'required|integer|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['msg' => 'Product not found'], 404);
        }

        $product->title = $request->input('txtName_' . $id);
        $product->description = $request->input('txtDescription_' . $id);
        $product->price = $request->input('txtPrice_' . $id);
        $product->quantity = $request->input('txtStock_' . $id);
        $product->category = $request->input('cmbCategories_' . $id);

        if ($request->hasFile('txtPhoto_' . $id)) {
            $image = $request->file('txtPhoto_' . $id);
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return response()->json(['msg' => 'Product updated successfully!']);
    }

    public function delete_product($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['msg' => 'Product not found'], 404);
        }

        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }

        $product->delete();

        return response()->json(['msg' => 'Product deleted successfully!']);
    }

    public function productSearch(Request $request)
    {
        $search = $request->input('q');
        $products = Product::where('title', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhere('category', 'like', '%' . $search . '%')
            ->paginate(10); // Adjust the pagination as per your requirement
        $categories = Category::all(); // Example of retrieving categories
        return view('admin.search_results', compact('products', 'search','categories'));
    }

    public function view_order()
    {
        // Retrieve orders with their related products and the associated user
        $orders = Order::with('products', 'user')->get();

        return view('admin.order', compact('orders'));
    }

    public function change_status(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:on the way,delivered'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $order = Order::findOrFail($id);
            $order->status = $request->status;
            $order->save();

            // Optionally, handle order deletion if delivered
            if ($request->status === 'delivered') {
                $order->delete(); // Delete the order if it's marked as delivered
            }

            return response()->json(['message' => 'Order status updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update order status'], 500);
        }
    }




    public function on_the_way($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'on the way';
        $order->save();

        return response()->json(['message' => 'Order status updated to "On the way"']);
    }

    public function delivered($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'Delivered';
        $order->save();

        return response()->json(['message' => 'Order status updated to "Delivered"']);
    }


    public function delete_order($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete order'], 500);
        }
    }
}
