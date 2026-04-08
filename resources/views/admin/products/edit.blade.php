@extends('layouts.admin')

@section('content')
<div class="min-h-screen py-12 px-4" style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);">
    <div class="max-w-3xl mx-auto">
        
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-amber-900/40 hover:text-[#2d1102] font-black uppercase text-[10px] tracking-widest transition mb-6">
            <span>⬅️</span> Back to Menu Items
        </a>

        <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/20 overflow-hidden">
            {{-- Header: Deep Espresso --}}
            <div class="bg-[#2d1102] p-10 text-white">
                <h1 class="text-3xl font-black uppercase tracking-tight">Edit Menu Item</h1>
                <p class="text-amber-200/60 mt-2 font-medium">Update pricing, category, or inventory photos for <span class="text-amber-400">"{{ $product->name }}"</span></p>
            </div>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-amber-900/40 uppercase tracking-widest">Product Name</label>
                        <input type="text" name="name" value="{{ $product->name }}" 
                               class="w-full px-5 py-4 rounded-2xl border border-amber-100/50 bg-amber-50/30 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition font-bold text-[#2d1102]">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-amber-900/40 uppercase tracking-widest">Category</label>
                        <select name="category_id" class="w-full px-5 py-4 rounded-2xl border border-amber-100/50 bg-amber-50/30 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition font-bold text-[#2d1102] appearance-none">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-amber-900/40 uppercase tracking-widest">Unit Price ($)</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-amber-700 font-black">$</span>
                            <input type="number" step="0.01" name="price" value="{{ $product->price }}" 
                                   class="w-full pl-10 pr-5 py-4 rounded-2xl border border-amber-100/50 bg-amber-50/30 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition font-bold text-[#2d1102]">
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-amber-900/40 uppercase tracking-widest">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                               class="w-full px-5 py-4 rounded-2xl border border-amber-100/50 bg-amber-50/30 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition font-bold text-[#2d1102]" 
                               placeholder="e.g. 50">
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black text-amber-900/40 uppercase tracking-widest">Update Photo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-2xl bg-amber-50 border-2 border-dashed border-amber-200 flex items-center justify-center overflow-hidden">
                                @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" class="object-cover w-full h-full">
                                @else
                                    <span class="text-3xl opacity-20">📷</span>
                                @endif
                            </div>
                            <input type="file" name="image" class="text-xs text-amber-900/40 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-[#4a2c2a] file:text-white hover:file:bg-[#2d1102] transition">
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-amber-50 flex items-center justify-between">
                    <button type="button" onclick="window.history.back()" class="text-amber-900/40 font-black uppercase text-[10px] tracking-widest hover:text-[#2d1102] transition">Cancel Changes</button>
                    
                    <button type="submit" class="px-10 py-4 bg-[#4a2c2a] hover:bg-[#2d1102] text-white font-black rounded-2xl shadow-lg shadow-amber-900/10 transition transform active:scale-95 flex items-center gap-3 uppercase text-xs tracking-widest">
                        💾 Update Menu Item
                    </button>
                </div>
            </form>
        </div>

        {{-- Tip Box: Amber Styled --}}
        <div class="mt-8 p-6 bg-amber-50/50 rounded-[2rem] border border-amber-100/50 flex items-center gap-4">
            <span class="text-2xl">💡</span>
            <p class="text-[11px] text-amber-900/70 leading-relaxed font-medium">
                Updating the price here will affect all <strong class="text-amber-900">future orders</strong>. It will not change the records for orders already completed in the system.
            </p>
        </div>
    </div>
</div>
@endsection