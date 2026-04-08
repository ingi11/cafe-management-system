@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen" style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);">
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('admin.inventory.index') }}" class="text-amber-900/40 hover:text-[#2d1102] font-black uppercase text-[10px] tracking-widest mb-6 inline-block transition-colors">
            ← Cancel and Go Back
        </a>

        {{-- Container: White background for clarity --}}
        <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/20 p-8">
            <h1 class="text-2xl font-black text-[#2d1102] mb-6 uppercase tracking-tight">✏️ Edit Item: {{ $item->item_name }}</h1>

            <form action="{{ route('admin.inventory.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    {{-- Item Name --}}
                    <div>
                        <label class="block text-xs font-black text-amber-900/40 uppercase mb-2 tracking-widest">Item Name</label>
                        <input type="text" name="item_name" value="{{ $item->item_name }}" class="w-full p-4 bg-amber-50/30 rounded-xl border-none focus:ring-2 focus:ring-amber-500 outline-none text-[#2d1102] font-bold" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Quantity --}}
                        <div>
                            <label class="block text-xs font-black text-amber-900/40 uppercase mb-2 tracking-widest">Current Stock</label>
                            <input type="number" step="0.01" name="quantity" value="{{ $item->quantity }}" class="w-full p-4 bg-amber-50/30 rounded-xl border-none focus:ring-2 focus:ring-amber-500 outline-none text-[#2d1102] font-bold" required>
                        </div>
                        {{-- Unit --}}
                        <div>
                            <label class="block text-xs font-black text-amber-900/40 uppercase mb-2 tracking-widest">Unit (kg, L, pcs)</label>
                            <input type="text" name="unit" value="{{ $item->unit }}" class="w-full p-4 bg-amber-50/30 rounded-xl border-none focus:ring-2 focus:ring-amber-500 outline-none text-[#2d1102] font-bold" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Category Selection --}}
                        <div>
                            <label class="block text-xs font-black text-amber-900/40 uppercase mb-2 tracking-widest">Category</label>
                            <select name="category_id" required class="w-full p-4 bg-amber-50/30 rounded-xl border-none focus:ring-2 focus:ring-amber-500 outline-none font-bold text-[#4a2c2a] appearance-none">
                                <option value="" disabled>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- Supplier Selection --}}
                        <div>
                            <label class="block text-xs font-black text-amber-900/40 uppercase mb-2 tracking-widest">Supplier</label>
                            <select name="supplier_id" class="w-full p-4 bg-amber-50/30 rounded-xl border-none focus:ring-2 focus:ring-amber-500 outline-none font-bold text-[#4a2c2a] appearance-none">
                                <option value="">No Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $item->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-amber-900/40 uppercase mb-2 tracking-widest">Cost Price (Per Unit)</label>
                        <input type="number" step="0.01" name="cost_price" value="{{ $item->cost_price }}" class="w-full p-4 bg-amber-50/30 rounded-xl border-none focus:ring-2 focus:ring-amber-500 outline-none text-[#2d1102] font-bold" placeholder="0.00">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-amber-900/40 uppercase mb-2 tracking-widest">Min Stock Level (Alert Threshold)</label>
                        <input type="number" name="min_stock_level" value="{{ $item->min_stock_level }}" class="w-full p-4 bg-amber-50/30 rounded-xl border-none focus:ring-2 focus:ring-amber-500 outline-none text-[#2d1102] font-bold" required>
                    </div>

                    <button type="submit" class="w-full bg-[#4a2c2a] hover:bg-[#2d1102] text-white font-black py-4 rounded-xl transition shadow-lg shadow-amber-900/10 mt-4 uppercase tracking-widest">
                        Update Stock Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection