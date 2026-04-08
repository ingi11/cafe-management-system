@extends('layouts.admin')

@section('content')
<div class="py-12 min-h-screen" style="background: linear-gradient(120deg, #fafafa 0%, #fbfbfb 100%);">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header & Filter Bar --}}
        <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-6 mb-8 bg-white p-8 rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/20">
            <div>
                <h2 class="text-3xl font-black text-[#2d1102] flex items-center gap-3 uppercase tracking-tighter">
                    <span class="p-3 bg-[#2d1102] rounded-2xl text-white text-xl shadow-lg shadow-amber-900/20">📊</span>
                    Financial Overview
                </h2>
                <p class="text-xs text-amber-900/50 mt-1 font-bold uppercase tracking-widest">
                    Analysis: <span class="text-amber-700">{{ \Carbon\Carbon::parse($startStr)->format('M d') }} - {{ \Carbon\Carbon::parse($endStr)->format('M d, Y') }}</span>
                </p>
            </div>

            <form action="{{ route('admin.report') }}" method="GET" class="flex items-center gap-3">
                <div class="flex items-center bg-amber-50/50 border border-amber-100/50 rounded-2xl px-5 py-2 focus-within:ring-4 focus-within:ring-amber-100 focus-within:border-amber-500 transition-all">
                    <div class="flex flex-col">
                        <label class="text-[9px] uppercase font-black text-amber-900/30 leading-none tracking-widest">From</label>
                        <input type="date" name="start_date" value="{{ $startStr }}" 
                            class="bg-transparent border-none p-0 text-sm font-black text-[#2d1102] focus:ring-0 cursor-pointer">
                    </div>
                    
                    <div class="h-8 w-[1px] bg-amber-200/50 mx-4"></div>

                    <div class="flex flex-col">
                        <label class="text-[9px] uppercase font-black text-amber-900/30 leading-none tracking-widest">To</label>
                        <input type="date" name="end_date" value="{{ $endStr }}" 
                            class="bg-transparent border-none p-0 text-sm font-black text-[#2d1102] focus:ring-0 cursor-pointer">
                    </div>
                </div>

                <button type="submit" class="bg-[#4a2c2a] hover:bg-[#2d1102] text-white p-4 rounded-2xl shadow-lg shadow-amber-900/10 transition-all active:scale-95" title="Apply Filter">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                @if(request('start_date'))
                    <a href="{{ route('admin.report') }}" class="text-[10px] font-black text-amber-900/40 hover:text-red-500 uppercase tracking-widest px-2 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Active Filter Notification --}}
        <div class="mb-8 p-5 bg-[#4a2c2a] text-amber-100 rounded-[1.5rem] shadow-lg flex items-center gap-3">
            <span class="text-lg">📅</span>
            <p class="text-[11px] font-bold uppercase tracking-widest">
                @if($filter == 'today')
                    Showing data for today, <span class="text-amber-400">{{ now()->format('M d, Y') }}</span>
                @elseif($filter == 'weekly')
                    Weekly: <span class="text-amber-400">{{ now()->startOfWeek()->format('M d') }}</span> to <span class="text-amber-400">{{ now()->endOfWeek()->format('M d') }}</span>
                @else
                    Monthly: <span class="text-amber-400">{{ now()->format('F Y') }}</span>
                @endif
            </p>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border-b-4 border-green-500">
                <p class="text-[10px] text-amber-900/40 uppercase font-black tracking-widest mb-1">Total Revenue</p>
                <p class="text-4xl font-black text-[#2d1102]">${{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border-b-4 border-amber-500">
                <p class="text-[10px] text-amber-900/40 uppercase font-black tracking-widest mb-1">Total Orders</p>
                <p class="text-4xl font-black text-[#2d1102]">{{ $totalOrders }}</p>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border-b-4 border-[#4a2c2a]">
                <p class="text-[10px] text-amber-900/40 uppercase font-black tracking-widest mb-1">Items Sold</p>
                <p class="text-4xl font-black text-[#2d1102]">{{ $totalItemsSold }}</p>
            </div>
        </div>

        {{-- Data Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-amber-100/50 overflow-hidden">
                <div class="bg-amber-50/50 px-8 py-5 border-b border-amber-100/50">
                    <h3 class="font-black text-[#2d1102] uppercase text-xs tracking-widest">Sales by Category</h3>
                </div>
                <table class="min-w-full divide-y divide-amber-50">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-amber-900/30 uppercase tracking-widest">Category</th>
                            <th class="px-8 py-4 text-center text-[10px] font-black text-amber-900/30 uppercase tracking-widest">Qty</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-amber-900/30 uppercase tracking-widest">Earnings</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-amber-50">
                        @foreach($categorySales as $category)
                            <tr class="hover:bg-amber-50/20 transition">
                                <td class="px-8 py-4 font-bold text-[#2d1102]">{{ $category->name }}</td>
                                <td class="px-8 py-4 text-center font-bold text-amber-700">{{ $category->total_qty }}</td>
                                <td class="px-8 py-4 text-right font-black text-[#2d1102]">${{ number_format($category->total_earned, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-amber-100/50 overflow-hidden">
                <div class="bg-amber-50/50 px-8 py-5 border-b border-amber-100/50">
                    <h3 class="font-black text-[#2d1102] uppercase text-xs tracking-widest">Staff Performance</h3>
                </div>
                <table class="min-w-full divide-y divide-amber-50">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-amber-900/30 uppercase tracking-widest">Staff Name</th>
                            <th class="px-8 py-4 text-center text-[10px] font-black text-amber-900/30 uppercase tracking-widest">Orders</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-amber-900/30 uppercase tracking-widest">Sales</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-amber-50">
                        @foreach($cashierSales as $staff)
                            <tr class="hover:bg-amber-50/20 transition">
                                <td class="px-8 py-4 font-bold text-[#2d1102]">{{ $staff->name }}</td>
                                <td class="px-8 py-4 text-center font-bold text-amber-700">{{ $staff->orders_count }}</td>
                                <td class="px-8 py-4 text-right font-black text-[#2d1102]">${{ number_format($staff->orders_sum_total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Product Grid Sold --}}
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-amber-100/50 overflow-hidden">
                <div class="bg-amber-50/50 px-8 py-5 border-b border-amber-100/50">
                    <h3 class="font-black text-[#2d1102] uppercase text-xs tracking-widest">Detailed Product Sales ({{ ucfirst($filter) }})</h3>
                </div>
                <div class="p-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($stockSold as $item)
                    <div class="border border-amber-100 rounded-[1.5rem] p-5 text-center bg-amber-50/30 hover:bg-amber-50 hover:border-amber-200 transition">
                        <p class="text-[9px] text-amber-900/40 uppercase font-black tracking-widest mb-1">{{ $item->name }}</p>
                        <p class="text-3xl font-black text-amber-700">{{ $item->order_items_sum_quantity }}</p>
                        <p class="text-[9px] text-amber-900/30 font-bold uppercase tracking-tighter">Units Sold</p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@endsection