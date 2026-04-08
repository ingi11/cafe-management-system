@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen" style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);">
    <div class="w-full max-w-md mx-auto my-10">
        {{-- Container: White background with soft Amber shadow --}}
        <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/20 p-10">
            
            <h2 class="text-2xl font-black text-[#2d1102] text-center mb-8 uppercase tracking-tight">Add New Supply</h2>
            
            <form action="{{ route('admin.inventory.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Item Name --}}
                <div class="space-y-2">
                    <label class="block text-sm font-black text-[#4a2c2a] ml-1 uppercase tracking-widest">Item Name</label>
                    <input type="text" name="item_name" placeholder="e.g. Arabica Beans" 
                           class="w-full px-5 py-3 bg-amber-50/30 border border-amber-100/50 rounded-xl focus:ring-2 focus:ring-amber-500 transition-all outline-none text-[#2d1102] placeholder-amber-900/20" required>
                </div>

                {{-- Category Selection --}}
                <div class="space-y-2">
                    <label class="block text-sm font-black text-[#4a2c2a] ml-1 uppercase tracking-widest">Category</label>
                    <select name="category_id" required 
                            class="w-full px-5 py-3 bg-amber-50/30 border border-amber-100/50 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none transition-all text-[#2d1102] appearance-none font-bold">
                        <option value="" disabled selected>Select a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-amber-900/40 font-medium ml-1">Items must have a category to appear in the Menu creation list.</p>
                </div>

                {{-- Supplier Selection --}}
                <div class="space-y-2">
                    <label class="block text-sm font-black text-[#4a2c2a] ml-1 uppercase tracking-widest">Supplier</label>
                    <select name="supplier_id" 
                            class="w-full px-5 py-3 bg-amber-50/30 border border-amber-100/50 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none transition-all text-[#2d1102] appearance-none">
                        <option value="">Select a Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-[#4a2c2a] ml-1 uppercase tracking-widest">Quantity</label>
                        <input type="number" step="0.01" name="quantity" placeholder="0" class="w-full px-5 py-3 bg-amber-50/30 border border-amber-100/50 rounded-xl outline-none focus:ring-2 focus:ring-amber-500 text-[#2d1102]" required>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-[#4a2c2a] ml-1 uppercase tracking-widest">Unit</label>
                        <input type="text" name="unit" placeholder="kg, L, bags" class="w-full px-5 py-3 bg-amber-50/30 border border-amber-100/50 rounded-xl outline-none focus:ring-2 focus:ring-amber-500 text-[#2d1102]" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-[#4a2c2a] ml-1 uppercase tracking-widest">Cost / Unit</label>
                        <input type="number" step="0.01" name="cost_price" placeholder="0.00" class="w-full px-5 py-3 bg-amber-50/30 border border-amber-100/50 rounded-xl outline-none focus:ring-2 focus:ring-amber-500 text-[#2d1102]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-black text-[#4a2c2a] ml-1 uppercase tracking-widest">Alert Level</label>
                        <input type="number" name="min_stock_level" value="5" class="w-full px-5 py-3 bg-amber-50/30 border border-amber-100/50 rounded-xl outline-none focus:ring-2 focus:ring-amber-500 text-[#2d1102]">
                    </div>
                </div>

                <div class="pt-4 space-y-4">
                    <button type="submit" class="w-full bg-[#4a2c2a] hover:bg-[#2d1102] text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-amber-900/10 transition-all transform hover:scale-[1.02]">
                        Save to Inventory
                    </button>
                    <a href="{{ route('admin.inventory.index') }}" class="block text-center text-[10px] font-black uppercase tracking-widest text-amber-900/40 hover:text-[#2d1102] transition-colors">
                        ← Back to List
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection