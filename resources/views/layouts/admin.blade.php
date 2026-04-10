<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        
        .cafe-body-bg {
            background: linear-gradient(135deg, #fffdfa 0%, #fcf5ed 100%);
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    </style>
</head>
<body class="cafe-body-bg flex min-h-screen font-sans antialiased text-slate-800">

    <nav class="w-72 bg-[#4a2c2a] text-white min-h-screen p-6 space-y-2 shadow-[4px_0_24px_rgba(0,0,0,0.05)] shrink-0 z-50">
        
        <div class="mb-10 pb-6 border-b border-white/5">
            <h1 class="text-2xl font-black tracking-tight text-white flex items-center gap-3">
                <span class="bg-white/10 p-2 rounded-xl">☕️</span> 
                <span class="drop-shadow-sm">Cafe Admin</span>
            </h1>
            <div class="mt-4 flex items-center gap-2 px-1">
                <div class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                </div>
                <p class="text-[10px] text-white/50 uppercase tracking-[0.25em] font-black">
                    {{ auth()->user()->role }} SESSION
                </p>
            </div>
        </div>

        <div class="space-y-1.5">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                    ['route' => 'admin.orders.index', 'icon' => '🛒', 'label' => 'Orders'],
                    ['route' => 'admin.report', 'icon' => '📈', 'label' => 'Daily Report', 'adminOnly' => true],
                    ['route' => 'admin.products.index', 'icon' => '☕️', 'label' => 'Menu Items'],
                    ['route' => 'admin.categories.index', 'icon' => '📁', 'label' => 'Categories'],
                    ['route' => 'admin.inventory.index', 'icon' => '📦', 'label' => 'Inventory'],
                    ['route' => 'admin.suppliers.index', 'icon' => '🏭', 'label' => 'Suppliers', 'adminOnly' => true],
                ];
            @endphp

            @foreach($navItems as $item)
                @if(!isset($item['adminOnly']) || (isset($item['adminOnly']) && auth()->user()->role == 'admin'))
                    <a href="{{ route($item['route']) }}" 
                       class="flex items-center gap-3 py-3 px-4 rounded-2xl transition-all duration-300 font-bold group
                       {{ request()->routeIs($item['route']) 
                          ? 'bg-gradient-to-r from-amber-600 to-amber-500 text-white shadow-[0_10px_20px_rgba(217,119,6,0.3)]' 
                          : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                        <span class="text-lg opacity-90 group-hover:scale-110 transition-transform">{{ $item['icon'] }}</span> 
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </div>

        @if(auth()->user()->role === 'admin')
            <div class="pt-8 mt-8 border-t border-white/5">
                <p class="px-4 mb-3 text-[10px] font-black text-white/30 uppercase tracking-[0.3em]">Management</p>
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 py-3 px-4 rounded-2xl transition-all font-bold group
                   {{ request()->routeIs('admin.users.*') 
                      ? 'bg-gradient-to-r from-amber-600 to-amber-500 text-white shadow-[0_10px_20px_rgba(217,119,6,0.3)]' 
                      : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <span class="text-lg group-hover:rotate-12 transition-transform">👥</span> Manage Staff
                </a>
            </div>
        @endif

        <div class="pt-10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-between px-5 py-4 bg-white/5 hover:bg-red-500/10 text-white/40 hover:text-red-400 rounded-2xl transition-all group font-black text-[10px] uppercase tracking-widest border border-white/5 hover:border-red-500/20">
                    <span>Sign Out</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-1 p-12 overflow-y-auto">
        <div class="max-w-7xl mx-auto">
            @yield('content') 
        </div>
    </main>

</body>
</html>