@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen" style="background: linear-gradient(120deg, #fefcf7 0%, #fafafa 100%);">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-black text-[#2d1102] uppercase tracking-tight">📦 Stock Inventory</h1>
                <p class="text-amber-900/60 text-sm font-medium">Manage your ingredients and track supplier costs.</p>
            </div>
            
            {{-- Only Admin can see the 'Add' button --}}
            @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.inventory.create') }}" 
                    class="bg-[#4a2c2a] hover:bg-[#2d1102] text-white px-6 py-3 rounded-xl font-black shadow-lg shadow-amber-900/10 transition-all transform hover:scale-105 inline-block uppercase text-xs tracking-widest">
                    + Add New Stock Item
                </a>
            @endif
        </div>

        {{-- Main Table Container - Background changed to white --}}
        <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/20 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-amber-50/30 border-b border-amber-100/50">
                    <tr>
                        <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest">Item Name</th>
                        
                        {{-- Admin Only Headers --}}
                        @if(auth()->user()->role == 'admin')
                            <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest">Supplier</th>
                            <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest text-center">Cost/Unit</th>
                        @endif

                        {{-- Public Headers (Visible to Cashier) --}}
                        <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest">Current Stock</th>
                        <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest">Status</th>
                        
                        @if(auth()->user()->role == 'admin')
                            <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest text-right">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-50/50">
                    @foreach($inventory as $item) 
                    <tr class="hover:bg-amber-50/20 transition">
                        <td class="p-5">
                            <p class="font-bold text-[#2d1102]">{{ $item->item_name }}</p>
                            <p class="text-[10px] text-amber-900/40 font-black uppercase tracking-tighter">Min: {{ $item->min_stock_level }} {{ $item->unit }}</p>
                        </td>
                        
                        {{-- Admin Only Columns --}}
                        @if(auth()->user()->role == 'admin')
                            <td class="p-5 text-sm">
                                @if($item->supplier)
                                    <span class="font-bold text-amber-700">{{ $item->supplier->name }}</span>
                                @else
                                    <span class="text-amber-200 italic">No Supplier</span>
                                @endif
                            </td>

                            <td class="p-5 text-center">
                                <span class="bg-amber-50 px-2 py-1 rounded text-sm font-mono font-bold text-amber-800 border border-amber-100/50">
                                    ${{ number_format($item->cost_price ?? 0, 2) }}
                                </span>
                            </td>
                        @endif

                        {{-- Public Columns --}}
                        <td class="p-5 text-[#4a2c2a] font-bold">
                            {{ $item->quantity }} <span class="text-[10px] text-amber-900/40 uppercase">{{ $item->unit }}</span>
                        </td>
                        
                        <td class="p-5">
                            @if($item->quantity <= ($item->min_stock_level ?? 0))
                                <span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    ⚠️ Low Stock
                                </span>
                            @else
                                <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    ✅ Healthy
                                </span>
                            @endif
                        </td>

                        {{-- Admin Only Actions --}}
                        @if(auth()->user()->role == 'admin')
                            <td class="p-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.inventory.edit', $item->id) }}" class="text-[#4a2c2a] hover:text-black font-black text-[10px] uppercase tracking-widest bg-amber-50 px-3 py-2 rounded-lg transition border border-amber-100/50">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.inventory.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 p-2 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection