<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Inventory; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class ProductController extends Controller
{
    
public function index() 
{
    // Added 'inventory' to the with() array to enable the auto-sync logic
    $products = Product::with(['category', 'inventory'])
        ->where('status', 'active')
        ->get();
        
    return view('admin.products.index', compact('products'));
}
   
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
    
public function inactiveIndex() 
{
    $products = Product::where('status', 'inactive')->get();
    return view('admin.products.inactivate', compact('products'));
}
public function deactivate(Product $product) 
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