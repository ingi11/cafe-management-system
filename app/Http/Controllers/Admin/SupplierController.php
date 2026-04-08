<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Inventory;

class SupplierController extends Controller
{
    
    public function index()
{
    // Eager loading 'inventories' is required for this loop to work efficiently
    $suppliers = Supplier::with('inventories')->get();
    return view('admin.suppliers.index', compact('suppliers'));
}

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Supplier::create($request->all());
        return back()->with('success', 'Supplier added successfully!');
    }
    public function edit($id)
{
    $supplier = Supplier::findOrFail($id);
    return view('admin.suppliers.edit', compact('supplier'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'nullable|email',
    ]);

    $supplier = Supplier::findOrFail($id);
    $supplier->update($request->all());

    return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated!');
}
}