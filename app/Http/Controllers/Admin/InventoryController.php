<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Supplier;
use App\Models\Category; // 1. Added Category Model import

class InventoryController extends Controller
{
    // List all stock with Supplier and Category info
    public function index()
    {
        // Added 'category' to eager loading to show category names in the table if needed
        $inventory = Inventory::with(['supplier', 'category'])->get();
        return view('admin.inventory.index', compact('inventory'));
    }

    // Show form to add new stock
    public function create() 
    {
        $suppliers = Supplier::all();
        $categories = Category::all(); // 2. Load categories for the new dropdown
        return view('admin.inventory.create', compact('suppliers', 'categories'));
    }

    // Save new stock
    public function store(Request $request) 
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Only admins can add inventory.');
        }

        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string', 
            'min_stock_level' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id', // 3. Validate category selection
            'cost_price' => 'nullable|numeric|min:0'
        ]);

        Inventory::create($request->all());
        
        return redirect()->route('admin.inventory.index')->with('success', 'Stock added!');
    }

    // Show the form to edit an existing ingredient
    public function edit($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized. Only admins can modify inventory.');
        }

        $item = Inventory::findOrFail($id);
        $suppliers = Supplier::all(); 
        $categories = Category::all(); // 4. Load categories for the edit dropdown
        
        return view('admin.inventory.edit', compact('item', 'suppliers', 'categories'));
    }

    // Update the ingredient in the database
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'min_stock_level' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id', // 5. Added category validation
            'cost_price' => 'nullable|numeric|min:0'
        ]);

        $item = Inventory::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('admin.inventory.index')->with('success', 'Stock updated successfully!');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $item = Inventory::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Item removed from inventory.');
    }
}