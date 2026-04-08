@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen" style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);">
    <div class="mb-8">
        <a href="{{ route('admin.products.index') }}" class="text-amber-900/40 hover:text-[#2d1102] font-black uppercase text-[10px] tracking-widest flex items-center gap-2 mb-4 transition">
            ← Back to Active Menu
        </a>
        <h1 class="text-3xl font-black text-[#2d1102] uppercase tracking-tight">🌑 Hidden Items</h1>
        <p class="text-amber-900/60 font-medium">Items that are currently deactivated and hidden from the customer menu.</p>
    </div>

    @if($products->isEmpty())
        <div class="bg-white/50 rounded-[2.5rem] p-12 text-center border-2 border-dashed border-amber-200/50">
            <p class="text-amber-900/30 font-black uppercase tracking-widest text-sm">No hidden items found.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.02)] border border-amber-100/10 overflow-hidden opacity-60 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-500 group">
                
                {{-- Image Section --}}
                <div class="h-40 bg-amber-50/50 flex items-center justify-center overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl opacity-20 text-[#2d1102]">☕</span>
                    @endif
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-black text-lg text-amber-900/40 line-through tracking-tight">{{ $product->name }}</h3>
                        <span class="text-amber-900/30 font-black tracking-tighter">${{ number_format($product->price, 2) }}</span>
                    </div>
                    
                    <p class="text-[10px] text-amber-900/20 uppercase font-black tracking-widest mb-4">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </p>

                    <div class="mt-6 pt-4 border-t border-amber-50">
                        <form action="{{ route('admin.products.deactivate', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full bg-[#4a2c2a] hover:bg-[#2d1102] text-white py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-amber-900/10 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                                ✅ Restore to Menu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection