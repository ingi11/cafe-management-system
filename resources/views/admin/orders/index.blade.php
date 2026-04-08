@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen" style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-black text-[#2d1102] uppercase tracking-tight">🛒 Customer Orders</h1>
                <p class="text-amber-900/60 font-medium">Track and process daily sales</p>
            </div>
            
            <a href="{{ route('admin.orders.create') }}" class="bg-[#4a2c2a] text-white px-6 py-3 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-amber-900/10 hover:bg-[#2d1102] transition transform hover:scale-105">
                + New Order
            </a>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/20 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-amber-50/50 border-b border-amber-100/50">
                    <tr>
                        <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest">Order ID</th>
                        <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest">Items Detail</th>
                        <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest text-center">Status</th>
                        <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest text-right">Total</th>
                        <th class="p-5 text-amber-900/40 font-black uppercase text-[10px] tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-50">
                    @foreach($orders as $order)
                    <tr class="hover:bg-amber-50/30 transition-colors group">
                        <td class="p-5">
                            <span class="font-black text-[#2d1102] text-sm">#{{ $order->id }}</span>
                        </td>
                        
                        <td class="p-5">
                            <div class="text-[11px] leading-relaxed font-bold text-amber-900/60">
                                @foreach($order->items as $item)
                                    <div class="flex items-center gap-1">
                                        <span class="text-[#4a2c2a]">{{ $item->quantity }}x</span>
                                        <span>{{ $item->product->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="p-5 text-center">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tighter
                                {{ $order->status == 'completed' 
                                    ? 'bg-green-50 text-green-600 border border-green-100' 
                                    : 'bg-amber-100 text-amber-700 border border-amber-200' }}">
                                {{ $order->status }}
                            </span>
                        </td>

                        <td class="p-5 text-right">
                            <span class="text-amber-800 font-black text-base">${{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        
                        <td class="p-5 text-right">
                            @if($order->status == 'pending')
                            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="bg-[#4a2c2a] hover:bg-green-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md shadow-amber-900/5">
                                    Mark Done
                                </button>
                            </form>
                            @else
                                <span class="text-amber-900/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection