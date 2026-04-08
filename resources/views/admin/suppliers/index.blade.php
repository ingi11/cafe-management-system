@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen" style="background: linear-gradient(120deg, #f7f7f7 0%, #fbfbfb 100%);">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-black text-[#2d1102] uppercase tracking-tight">📦 Supplier Database</h1>
            <button onclick="toggleModal()" class="bg-[#4a2c2a] text-white px-6 py-3 rounded-xl font-bold shadow-[0_10px_20px_rgba(74,44,42,0.2)] hover:bg-[#2d1102] transition-all active:scale-95">
                + Add New Supplier
            </button>
        </div>

        <div class="bg-white/80 backdrop-blur-md rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/50 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-amber-50/50 border-b border-amber-100">
                    <tr>
                        <th class="p-5 text-xs font-black text-amber-900/40 uppercase tracking-widest">Supplier Name</th>
                        <th class="p-5 text-xs font-black text-amber-900/40 uppercase tracking-widest">Contact</th>
                        <th class="p-5 text-xs font-black text-amber-900/40 uppercase tracking-widest">Linked Items</th>
                        <th class="p-5 text-xs font-black text-amber-900/40 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-50">
                    @foreach($suppliers as $supplier)
                    <tr class="hover:bg-amber-50/30 transition">
                        <td class="p-5">
                            <p class="font-bold text-[#2d1102]">{{ $supplier->name }}</p>
                            <p class="text-xs text-amber-700/50">{{ $supplier->address }}</p>
                        </td>
                        <td class="p-5">
                            <p class="text-sm font-medium text-amber-900">{{ $supplier->phone }}</p>
                            <p class="text-xs text-amber-700/50">{{ $supplier->email }}</p>
                        </td>
                        <td class="p-5">
                            <div class="flex flex-wrap gap-2">
                                @forelse($supplier->inventories as $item)
                                    <a href="{{ route('admin.inventory.edit', $item->id) }}" 
                                    class="flex items-center bg-amber-50 border border-amber-100 rounded-lg px-2 py-1 hover:bg-amber-100 hover:border-amber-200 transition-all group">
                                        
                                        <span class="text-[10px] font-bold text-amber-800 uppercase mr-2 group-hover:text-[#2d1102]">
                                            {{ $item->item_name }}
                                        </span>
                                        
                                        <span class="text-[10px] font-black text-amber-900 border-l border-amber-200 pl-2">
                                            ${{ number_format($item->cost_price, 2) }}
                                        </span>
                                    </a>
                                @empty
                                    <span class="text-amber-200 text-[10px] italic font-bold uppercase tracking-tighter">No ingredients linked</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="p-5 text-right">
                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="text-[#4a2c2a] font-black text-xs hover:text-black tracking-widest hover:underline">
                                EDIT
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="supplierModal" class="fixed inset-0 bg-[#2d1102]/60 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white p-8 rounded-[2rem] w-full max-w-md shadow-2xl border border-amber-100">
        <h2 class="text-xl font-black mb-6 text-[#2d1102]">Register New Supplier</h2>
        <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Supplier Name" class="w-full p-4 bg-amber-50/50 border border-amber-100 text-[#2d1102] rounded-xl outline-none focus:ring-2 focus:ring-amber-500 placeholder-amber-900/30" required>
            <input type="text" name="phone" placeholder="Phone Number" class="w-full p-4 bg-amber-50/50 border border-amber-100 text-[#2d1102] rounded-xl outline-none focus:ring-2 focus:ring-amber-500 placeholder-amber-900/30">
            <input type="email" name="email" placeholder="Email Address" class="w-full p-4 bg-amber-50/50 border border-amber-100 text-[#2d1102] rounded-xl outline-none focus:ring-2 focus:ring-amber-500 placeholder-amber-900/30">
            <textarea name="address" placeholder="Address" class="w-full p-4 bg-amber-50/50 border border-amber-100 text-[#2d1102] rounded-xl outline-none focus:ring-2 focus:ring-amber-500 placeholder-amber-900/30"></textarea>
            
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="toggleModal()" class="flex-1 py-4 bg-amber-50 text-amber-900 font-bold rounded-xl border border-amber-100 hover:bg-amber-100 transition">Cancel</button>
                <button type="submit" class="flex-1 py-4 bg-[#4a2c2a] text-white font-bold rounded-xl shadow-lg hover:bg-[#2d1102] transition">Save Supplier</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal() {
        const modal = document.getElementById('supplierModal');
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }
</script>
@endsection