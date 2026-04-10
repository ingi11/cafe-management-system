@extends('layouts.admin')

@section('content')
<div class="container mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-gray-500">
            @if($startStr === $endStr)
                Viewing data for: <strong>{{ \Carbon\Carbon::parse($startStr)->format('M d, Y') }}</strong>
            @else
                Viewing: <strong>{{ \Carbon\Carbon::parse($startStr)->format('M d') }}</strong> to <strong>{{ \Carbon\Carbon::parse($endStr)->format('M d, Y') }}</strong>
            @endif
        </p>
    </div>

    <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-lg shadow-sm border">
        <div class="flex items-center gap-1">
            <label class="text-[10px] uppercase font-bold text-gray-400">From</label>
            <input type="date" name="start_date" value="{{ $startStr }}" class="border-none focus:ring-0 text-sm text-gray-600 p-1">
        </div>
        
        <div class="flex items-center gap-1 border-l pl-2">
            <label class="text-[10px] uppercase font-bold text-gray-400">To</label>
            <input type="date" name="end_date" value="{{ $endStr }}" class="border-none focus:ring-0 text-sm text-gray-600 p-1">
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-4 py-1 rounded text-sm font-bold ml-2">
            Filter
        </button>
        <a href="{{ route('dashboard') }}" class="text-xs text-gray-400 hover:text-indigo-600 px-2">Reset</a>
    </form>
</div>

    @if(auth()->user()->role === 'admin')
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 uppercase font-bold">Revenue</p>
        <h3 class="text-xl font-black text-indigo-600">${{ number_format($todayRevenue, 2) }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-green-100">
        <p class="text-xs text-green-600 uppercase font-bold"> Profit</p>
        <h3 class="text-xl font-black text-green-600">${{ number_format($todayProfit, 2) }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 uppercase font-bold">Orders</p>
        <h3 class="text-xl font-black text-gray-800">{{ $todayOrdersCount }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-xs text-gray-500 uppercase font-bold">Staff</p>
        <h3 class="text-xl font-black text-gray-800">{{ $totalStaff }}</h3>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-red-100">
        <p class="text-xs text-red-500 uppercase font-bold">Low Stock</p>
        <h3 class="text-xl font-black text-red-600">{{ $lowStockItems->count() }}</h3>
    </div>
</div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
            <h3 class="font-bold mb-4">Revenue Trend (Mockup)</h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="font-bold mb-4">Top Selling Items</h3>
            <div class="space-y-4">
                @foreach($topSelling as $item)
                    <div class="flex justify-between items-center border-b pb-2">
                        {{-- Change $item->product->name to just $item->name --}}
                        <span class="text-gray-700">{{ $item->name }}</span>
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold">
                            {{ $item->total_qty }} sold
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-indigo-900 text-white p-8 rounded-2xl shadow-xl flex justify-between items-center">
                <div>
                    <p class="opacity-80">Total Orders Processed Today</p>
                    <h3 class="text-5xl font-black">{{ $todayOrdersCount }}</h3>
                </div>
                <div class="hidden md:block">
                    <svg class="w-24 h-24 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"></path></svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">Your Recent Orders</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-400 text-sm uppercase">
                                <th class="pb-3">Order ID</th>
                                <th class="pb-3">Time</th>
                                <th class="pb-3">Total</th>
                                <th class="pb-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($recentOrders as $order)
                            <tr>
                                <td class="py-3 font-medium">#{{ $order->id }}</td>
                                <td class="py-3 text-gray-500 text-sm">{{ $order->created_at->diffForHumans() }}</td>
                                <td class="py-3 font-bold">${{ number_format($order->total_amount, 2) }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 rounded text-xs {{ $order->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold">Menu Preview</h3>
                <a href="{{ route('admin.products.index') }}" class="text-xs text-indigo-600 hover:underline">View All</a>
            </div>
            <div class="grid grid-cols-1 gap-3">
                
                @foreach($menuPreview as $product)
                    <div class="flex items-center gap-4 p-2">
                        <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            @if($product->image)
                                {{-- Make sure to use the asset('storage/...') helper --}}
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xl">☕</div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">{{ $product->name }}</h4>
                            <p class="text-indigo-600 font-bold text-xs">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @if(auth()->user()->role === 'admin')
    <div class="mt-8 bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-bold mb-4">Latest Store Activity</h3>
        <div class="space-y-4">
            @foreach($recentOrders as $order)
            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-4">
                    <div class="p-2 bg-indigo-100 text-indigo-700 rounded-full">🛒</div>
                    <div>
                        <p class="font-bold text-sm">Order #{{ $order->id }}</p>
                        <p class="text-xs text-gray-500">Processed by: {{ $order->user->name ?? 'System' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-indigo-600">${{ number_format($order->total_amount, 2) }}</p>
                    <p class="text-xs text-gray-400">{{ $order->created_at->format('h:i A') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart');
    if(ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($weeklyRevenueData) !!}, 
                    // borderColor: 'rgb(79, 70, 229)',
                    tension: 0.4,
                    fill: true,
                    borderColor: '#b45309', 
                    backgroundColor: 'rgba(180, 83, 9, 0.1)', 
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
</script>
@endsection