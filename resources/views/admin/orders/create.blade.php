@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 min-h-screen" style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);">
    <div class="bg-white rounded-[3rem] shadow-[0_20px_50px_rgba(45,17,2,0.05)] border border-amber-100/20 overflow-hidden">
        
        {{-- Header Section --}}
        <div class="p-10 border-b border-amber-50 bg-amber-50/20">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h2 class="text-4xl font-black text-[#2d1102] flex items-center gap-4 uppercase tracking-tighter">
                        <span class="p-4 bg-[#2d1102] rounded-[2rem] text-white text-2xl shadow-xl shadow-amber-900/20">📦</span>
                        Stock Supply
                    </h2>
                    <p class="text-amber-900/50 mt-2 font-medium">Direct inventory sales and distribution</p>
                </div>
                
                <div class="relative w-full md:w-96 group">
                    <span class="absolute inset-y-0 left-0 pl-5 flex items-center text-amber-900/30 group-focus-within:text-amber-600 transition-colors">🔍</span>
                    <input type="text" id="product-search" placeholder="Search stock items..." 
                           class="w-full pl-14 pr-6 py-4 rounded-[2rem] border border-amber-100 bg-white focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition font-bold text-[#2d1102]">
                </div>
            </div>

            {{-- Category Tabs --}}
            <div class="flex gap-3 mt-10 overflow-x-auto pb-2 scrollbar-hide">
                <button type="button" class="category-tab active px-10 py-4 rounded-2xl bg-[#4a2c2a] text-white font-black uppercase text-[10px] tracking-widest shadow-lg shadow-amber-900/10 transition" data-category="all">All Supplies</button>
                @foreach($categories as $category)
                    <button type="button" class="category-tab px-10 py-4 rounded-2xl bg-white border border-amber-100 text-amber-900/40 font-black uppercase text-[10px] tracking-widest hover:bg-amber-50 transition" 
                            data-category="cat-{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <form action="{{ route('admin.orders.store') }}" method="POST" class="p-10">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                {{-- Product List --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($products as $product)
                    <div class="product-card p-6 border border-amber-100/50 rounded-[2.5rem] bg-white hover:border-amber-500/30 hover:shadow-xl hover:shadow-amber-900/5 transition-all duration-300 group" 
                         data-name="{{ strtolower($product->name) }}" 
                         data-cat="cat-{{ $product->category_id }}">
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <div class="w-20 h-20 bg-amber-50 rounded-[1.5rem] flex items-center justify-center overflow-hidden border border-amber-100 group-hover:scale-105 transition duration-500">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($product->name) }}&background=fef9e8&color=2d1102';">
                                    @else
                                        <span class="text-4xl opacity-20">☕</span>
                                    @endif
                                </div>

                                <div>
                                    <p class="font-black text-[#2d1102] text-2xl leading-tight">{{ $product->name }}</p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-[10px] text-amber-700 font-black px-3 py-1 bg-amber-50 rounded-lg uppercase tracking-widest">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-[10px] text-amber-900/30 font-black uppercase tracking-widest">Stock: {{ $product->stock_quantity ?? '0' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 bg-amber-50/50 p-2 rounded-[1.5rem] border border-amber-100">
                                <span class="text-[9px] font-black text-amber-900/40 uppercase pl-3 tracking-widest">QTY</span>
                                <input type="number" name="items[{{ $product->id }}][quantity]" value="0" min="0" 
                                       class="qty-input w-24 px-3 py-3 rounded-xl border-none bg-white text-center font-black text-xl text-[#2d1102] shadow-sm focus:ring-2 focus:ring-amber-500">
                                <input type="hidden" class="base-price" value="{{ $product->price }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Sidebar Checkout --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">
                        <div class="bg-[#2d1102] text-white rounded-[3rem] p-12 shadow-2xl relative overflow-hidden">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl"></div>
                            
                            <h3 class="text-[10px] font-black text-amber-500 uppercase tracking-[0.3em] mb-10">Checkout Summary</h3>
                            
                            <div class="space-y-8 mb-12">
                                <div>
                                    <label class="block text-[10px] font-black text-amber-200/40 uppercase tracking-widest mb-3">Customer / Client</label>
                                    <input type="text" name="customer_name" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white outline-none focus:border-amber-500 focus:bg-white/10 transition" placeholder="Walk-in Client">
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-black text-amber-200/40 uppercase tracking-widest mb-3">Payment Method</label>
                                    <select name="payment_method" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white outline-none focus:border-amber-500 focus:bg-white/10 transition appearance-none">
                                        <option value="cash" class="text-black">💵 Cash Payment</option>
                                        <option value="ABA" class="text-black">📱 ABA Mobile Pay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="border-t border-white/10 pt-10">
                                <p class="text-[10px] text-amber-200/40 uppercase font-black tracking-widest">Total Payable</p>
                                <div class="flex items-baseline gap-2 mt-3">
                                    <span class="text-3xl font-bold text-amber-500">$</span>
                                    <span id="grand-total-display" class="text-7xl font-black text-amber-400 tracking-tighter">0.00</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-[#2d1102] py-6 rounded-2xl font-black uppercase text-xs tracking-[0.2em] mt-12 shadow-xl shadow-amber-900/20 transition transform active:scale-95 flex items-center justify-center gap-3">
                                <span>Proceed Order</span>
                                <span class="text-xl">➡️</span>
                            </button>
                        </div>
                        
                        <div class="p-8 bg-amber-50/50 rounded-[2.5rem] border border-amber-100/50">
                            <p class="text-[11px] text-amber-900/60 leading-relaxed italic font-medium">
                                💡 <strong>Inventory Note:</strong> Direct stock sales automatically deduct from the database in real-time.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('product-search');
    const categoryTabs = document.querySelectorAll('.category-tab');
    const productCards = document.querySelectorAll('.product-card');
    const grandTotalDisplay = document.getElementById('grand-total-display');

    function filterProducts() {
        const term = searchInput.value.toLowerCase();
        const activeTab = document.querySelector('.category-tab.active');
        const activeCat = activeTab ? activeTab.dataset.category : 'all';

        productCards.forEach(card => {
            const matchesSearch = card.dataset.name.includes(term);
            const matchesCat = (activeCat === 'all' || card.dataset.cat === activeCat);
            card.style.display = (matchesSearch && matchesCat) ? 'block' : 'none';
        });
    }

    searchInput.addEventListener('input', filterProducts);

    categoryTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            categoryTabs.forEach(t => {
                // Reset to unselected state
                t.classList.remove('active', 'bg-[#4a2c2a]', 'text-white');
                t.classList.add('bg-white', 'text-amber-900/40', 'border-amber-100');
            });
            // Set active state
            tab.classList.add('active', 'bg-[#4a2c2a]', 'text-white');
            tab.classList.remove('bg-white', 'text-amber-900/40', 'border-amber-100');
            filterProducts();
        });
    });

    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', () => {
            let total = 0;
            productCards.forEach(card => {
                const qty = parseInt(card.querySelector('.qty-input').value) || 0;
                const price = parseFloat(card.querySelector('.base-price').value) || 0;
                total += qty * price;
            });
            grandTotalDisplay.innerText = total.toFixed(2);
        });
    });
});
</script>
@endsection