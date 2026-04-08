<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Inventory; // Added this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class ProductController extends Controller
{
    // 1. List all products (Shared with Cashier)
    // public function index() 
    // {
    //     // We use 'with' to get the category name without slowing down the database
    //     $products = Product::with('category')->get();
    //     return view('admin.products.index', compact('products'));
    // }
//     public function index() 
// {
//     // Only fetch products where the status is 'active'
//     $products = Product::with('category')->where('status', 'active')->get();
//     return view('admin.products.index', compact('products'));
// }
public function index() 
{
    // Added 'inventory' to the with() array to enable the auto-sync logic
    $products = Product::with(['category', 'inventory'])
        ->where('status', 'active')
        ->get();
        
    return view('admin.products.index', compact('products'));
}
    // 2. Show form to create (Admin Only)
    // public function create() {
    //     $categories = Category::all();
    //     return view('admin.products.create', compact('categories'));
    // }
    public function create()
{
    // Fetch inventory items so the admin can see costs while setting prices
    $ingredients = Inventory::all();
    $categories = Category::all();
    return view('admin.products.create', compact('ingredients', 'categories'));
}

    // 3. Save new product
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock_quantity' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
        // This saves to storage/app/public/products
        // But returns the string "products/filename.jpg"
        $path = $request->file('image')->store('products', 'public');
        $data['image'] = $path;
    }

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Item added to menu!');
    }
    public function edit(Product $product)
{
    $categories = Category::all();
    return view('admin.products.edit', compact('product', 'categories'));
}
    // 4. Update existing product
//     public function update(Request $request, Product $product)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'price' => 'required|numeric|min:0',
//         'stock_quantity' => 'required|integer|min:0',
//         'category_id' => 'required|exists:categories,id',
//         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
//     ]);

//     // Get all input except the file itself
//     $data = $request->except('image');

//     if ($request->hasFile('image')) {
//         // 1. Delete old physical file
//         if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
//             Storage::disk('public')->delete($product->image_path);
//         }
        
//         // 2. Store new file and add the path to our $data array
//         $data['image_path'] = $request->file('image')->store('products', 'public');
//     }

//     // 3. Update the model with the combined data
//     $product->update($data);

//     return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
// }

// public function update(Request $request, $id)
// {
//     // Find the item first
//     $product = Product::findOrFail($id);

//     // 1. Validate the data
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'category_id' => 'required',
//         'price' => 'required|numeric|min:0',
//         'image' => 'nullable|image|max:2048', // Allow new photo
//     ]);

//     $data = $request->all();

//     // 2. Handle New Photo Upload
//     if ($request->hasFile('image')) {
//         // Option: Delete old image from storage here if you want to save space
//         $data['image'] = $request->file('image')->store('products', 'public');
//     }

//     // 3. Perform the update
//     $product->update($data);

//     return redirect()->route('admin.products.index')->with('success', 'Stock updated!');
// }



public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'image' => 'nullable|image|max:2048',
    ]);

    // Use except('image') so we don't accidentally save the File object to the DB
    $data = $request->except('image');

    if ($request->hasFile('image')) {
        // Delete old image if it exists to save disk space
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        // Store new image and save the string path
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);

    return redirect()->route('admin.products.index')->with('success', 'Menu item updated successfully!');
}
    // 5. Toggle Status (Deactivate/Reactivate)
    // public function deactivate(Product $product)
    // {
    //     $product->status = ($product->status === 'active') ? 'inactive' : 'active';
    //     $product->save();

    //     return back()->with('success', 'Status updated to ' . $product->status);
    // }
public function inactiveIndex() 
{
    $products = Product::where('status', 'inactive')->get();
    return view('admin.products.inactivate', compact('products'));
}
public function deactivate(Product $product) // Change $id to Product $product
{
    // Toggle the status
    $product->status = ($product->status === 'active') ? 'inactive' : 'active';
    $product->save();

    return redirect()->route('admin.products.index')->with('success', 'Product status updated!');
}
    // --- RECIPE / INGREDIENT LOGIC ---

    public function showIngredients($id)
    {
        $product = Product::with('recipes.inventory')->findOrFail($id);
        $inventory = Inventory::all();
        return view('admin.products.ingredients', compact('product', 'inventory'));
    }

    public function storeIngredient(Request $request, $id)
    {
        Recipe::create([
            'product_id' => $id,
            'inventory_id' => $request->inventory_id,
            'quantity_required' => $request->quantity_required,
        ]);

        return back()->with('success', 'Ingredient added to recipe!');
    }

    public function destroyIngredient($id)
    {
        Recipe::findOrFail($id)->delete();
        return back()->with('success', 'Ingredient removed.');
    }
}