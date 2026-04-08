@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">🍎 Recipe for {{ $product->name }}</h2>
                <p class="text-gray-500">Define what goes into one serving of this item.</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-600">Close</a>
        </div>

        <form action="{{ route('products.ingredients.store', $product->id) }}" method="POST" class="bg-gray-50 p-6 rounded-2xl mb-8 border border-dashed border-gray-300">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Select Supply</label>
                    <select name="inventory_id" class="w-full px-4 py-2 rounded-xl border border-gray-300 bg-white" required>
                        @foreach($inventory as $item)
                            <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-2">Qty per Serving</label>
                    <input type="number" name="quantity_required" step="0.001" placeholder="0.00" class="w-full px-4 py-2 rounded-xl border border-gray-300" required>
                </div>
                <button type="submit" class="bg-[#6f4e37] text-white py-2 rounded-xl font-bold hover:bg-[#5a3d2b] transition">
                    + Add to Recipe
                </button>
            </div>
        </form>

        <h3 class="font-bold text-gray-700 mb-4">Current Ingredients</h3>
        <table class="w-full text-left">
            <thead class="border-b">
                <tr>
                    <th class="py-3 text-gray-400 text-sm">Ingredient</th>
                    <th class="py-3 text-gray-400 text-sm text-center">Amount</th>
                    <th class="py-3 text-gray-400 text-sm text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->recipes as $recipe)
                <tr class="border-b last:border-0">
                    <td class="py-4 font-bold">{{ $recipe->inventory->name }}</td>
                    <td class="py-4 text-center">{{ $recipe->quantity_required }} {{ $recipe->inventory->unit }}</td>
                    <td class="py-4 text-right">
                        <form action="{{ route('products.ingredients.destroy', $recipe->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-red-400 hover:text-red-600 text-sm">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection