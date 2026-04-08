@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen" style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#2d1102] uppercase tracking-tight">☕ Menu Items</h1>
            <p class="text-amber-900/60 font-medium">View and manage cafe offerings</p>
        </div>
        
        {{-- ONLY ADMIN: Can add new products --}}
        @if(Auth::user()->role === 'admin')
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.products.inactive') }}" class="text-[10px] font-black uppercase tracking-widest text-amber-900/40 hover:text-amber-600 transition">
                View Hidden Items ({{ \App\Models\Product::where('status', 'inactive')->count() }})
            </a>
            <a href="{{ route('admin.products.create') }}" class="bg-[#4a2c2a] text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-amber-900/10 hover:bg-[#2d1102] transition transform hover:scale-105">
                + Add New Item
            </a>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/20 overflow-hidden hover:shadow-xl hover:shadow-amber-900/5 transition-all duration-300 group">
            
            <div class="h-48 bg-amber-50/50 flex items-center justify-center overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <span class="text-5xl opacity-20">☕</span>
                @endif
            </div>

            <div class="p-8">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-black text-xl text-[#2d1102] leading-tight">{{ $product->name }}</h3>
                    <span class="text-amber-700 font-black text-lg">${{ number_format($product->price, 2) }}</span>
                </div>
                
                <p class="text-[10px] text-amber-900/40 uppercase font-black tracking-widest mb-6">
                    {{ $product->category->name ?? 'Uncategorized' }}
                </p>

                <div class="flex items-center justify-between pt-6 border-t border-amber-50">
                    
                    <div>
                        @if($product->inventory)
                            <span class="text-[10px] uppercase tracking-widest font-black {{ $product->inventory->quantity > 0 ? 'text-green-600' : 'text-orange-600' }}">
                                {{ $product->inventory->quantity > 0 
                                    ? '● In Stock: ' . number_format($product->inventory->quantity, 0) . ' ' . $product->inventory->unit 
                                    : '○ Out of Stock' 
                                }}
                            </span>
                        @else
                            <span class="text-[10px] uppercase tracking-widest font-black text-amber-900/30 italic">
                                Manual: {{ $product->stock_quantity ?? 0 }}
                            </span>
                        @endif
                    </div>

                    {{-- ONLY ADMIN: Edit/Delete Actions --}}
                    @if(Auth::user()->role === 'admin')
                        <div class="flex gap-1">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 text-amber-900/40 hover:text-blue-500 hover:bg-blue-50 rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>

                            <form action="{{ route('admin.products.deactivate', $product->id) }}" method="POST" 
                                onsubmit="return confirm('Are you sure you want to deactivate this product?');">
                                @csrf
                                @method('PATCH')
                                
                                <button type="submit" class="p-2 text-amber-900/40 hover:text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection