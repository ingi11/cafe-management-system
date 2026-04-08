<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // 1. Show the list of categories
    public function index()
    {
         $categories = Category::withCount('products')->get();
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    // 2. Show the form to create a category
    public function create()
{
    // Fetch all categories so the Blade can loop through them
    $categories = Category::all(); 

    // Pass the variable to the view
    return view('admin.categories.create', compact('categories'));
}

    // 3. Save the category to the database
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|unique:categories|max:255',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //     ]);

    //     $data = $request->all();
        
    //     // Auto-generate a slug for cleaner URLs
    //     $data['slug'] = Str::slug($request->name);

    //     // Handle Image Upload
    //     if ($request->hasFile('image')) {
    //         $data['image'] = $request->file('image')->store('categories', 'public');
    //     }

    //     Category::create($data);

    //     return redirect()->route('admin.categories.index')->with('success', 'Category Created Successfully!');
    // }
    public function store(Request $request)
{
    // 1. Validate the incoming data
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'inventory_id' => 'nullable', // The link to stock item
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $data = $request->all();

    // 2. Handle Image Upload
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    // 3. Save to Database
    Product::create($data);

    return redirect()->route('admin.products.index')->with('success', 'Menu item added!');
}

    public function edit($id)
{
    if (auth()->user()->role !== 'admin') {
        abort(403, 'You do not have permission to edit categories.');
    }

    $category = Category::findOrFail($id);
    return view('admin.categories.edit', compact('category'));
}

public function update(Request $request, $id)
{
    if (auth()->user()->role !== 'admin') {
        abort(403, 'You do not have permission to update categories.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $category = Category::findOrFail($id);
    $data = $request->all();

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('categories', 'public');
    }

    $category->update($data);

    return redirect()->route('categories.index')->with('success', 'Category updated!');
}

public function destroy($id)
{
    $category = Category::findOrFail($id);
    $category->delete();

    return redirect()->route('categories.index')->with('success', 'Category deleted!');
}

    
}
