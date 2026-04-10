<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;




class AdminController extends Controller
{
//    public function index(Request $request)
// {
//     $user = auth()->user();
    

//         // 3. Pass it to the view
        
    
//     // 1. ALWAYS define these first so they exist for both Admin and Staff
//     $defaultDate = $request->get('date', \Carbon\Carbon::today()->format('Y-m-d'));
//     $startStr = $request->get('start_date', $defaultDate);
//     $endStr = $request->get('end_date', $defaultDate);

//     $startDate = \Carbon\Carbon::parse($startStr)->startOfDay();
//     $endDate = \Carbon\Carbon::parse($endStr)->endOfDay();

//     // 2. Shared Data
//     $todayOrdersCount = \App\Models\Order::whereBetween('created_at', [$startDate, $endDate])->count();
//     $recentOrders = \App\Models\Order::with('user')
//         ->whereBetween('created_at', [$startDate, $endDate])
//         ->latest()->take(5)->get();

//     if ($user->role === 'admin') {
//         // 3. Admin Specific Data
//         $todayRevenue = \App\Models\Order::where('status', 'completed')
//             ->whereBetween('created_at', [$startDate, $endDate])
//             ->sum('total_amount');

//         $todayCost = \Illuminate\Support\Facades\DB::table('order_items')
//             ->join('orders', 'order_items.order_id', '=', 'orders.id')
//             ->join('products', 'order_items.product_id', '=', 'products.id')
//             ->join('inventories', 'products.name', '=', 'inventories.item_name')
//             ->where('orders.status', 'completed')
//             ->whereBetween('orders.created_at', [$startDate, $endDate])
//             ->select(\Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity * inventories.cost_price) as total_cost'))
//             ->first()->total_cost ?? 0;

//         $todayProfit = $todayRevenue - $todayCost;
//         $totalStaff = \App\Models\User::count();
//         $lowStockItems = \App\Models\Inventory::whereColumn('quantity', '<', 'min_stock_level')->get();

//         $topSelling = \Illuminate\Support\Facades\DB::table('order_items')
//             ->join('products', 'order_items.product_id', '=', 'products.id')
//             ->select('products.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_qty'))
//             ->whereBetween('order_items.created_at', [$startDate, $endDate])
//             ->groupBy('products.id', 'products.name')
//             ->orderByDesc('total_qty')->take(5)->get();

//         $weeklyRevenueData = [];
//     $days = [];

//     for ($i = 6; $i >= 0; $i--) {
//         $date = \Carbon\Carbon::today()->subDays($i);
//         $days[] = $date->format('D'); // e.g., "Mon", "Tue"

//         $revenue = \App\Models\Order::where('status', 'completed')
//             ->whereDate('created_at', $date)
//             ->sum('total_amount');
            
//         $weeklyRevenueData[] = (float)$revenue;
//     }

//         return view('admin.dashboard', compact(
//             'todayRevenue', 'todayProfit', 'todayOrdersCount', 
//             'topSelling', 'recentOrders', 'lowStockItems', 
//             'totalStaff', 'startStr', 'endStr', 'weeklyRevenueData', 'days'
//         ));

//     } else {
//         // 4. Staff Specific Data
//         $menuPreview = \App\Models\Product::where('is_active', true)->take(6)->get();

//         return view('admin.dashboard', compact(
//             'todayOrdersCount', 'recentOrders', 'menuPreview', 'startStr', 'endStr'
//         ));
//     }
// }

public function index(Request $request)
{
    $user = auth()->user();
    
    // 1. Define dates first
    $defaultDate = \Carbon\Carbon::today()->format('Y-m-d');
    $startStr = $request->get('start_date', $defaultDate);
    $endStr = $request->get('end_date', $defaultDate);

    $startDate = \Carbon\Carbon::parse($startStr)->startOfDay();
    $endDate = \Carbon\Carbon::parse($endStr)->endOfDay();

    // 2. Shared Data for both Admin and Staff
    $todayOrdersCount = \App\Models\Order::whereBetween('created_at', [$startDate, $endDate])->count();
    $recentOrders = \App\Models\Order::with('user')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->latest()->take(5)->get();

    // --- CHART DATA 
    $weeklyRevenueData = [];
    $days = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = \Carbon\Carbon::today()->subDays($i);
        $days[] = $date->format('D'); 

        $revenue = \App\Models\Order::where('status', 'completed')
            ->whereDate('created_at', $date)
            ->sum('total_amount');
            
        $weeklyRevenueData[] = (float)$revenue;
    }
    // --------------------------------------------------------------

    if ($user->role === 'admin') {
        // 3. Admin Specific Logic
        $todayRevenue = \App\Models\Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        $todayCost = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('inventories', 'products.name', '=', 'inventories.item_name')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(\Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity * inventories.cost_price) as total_cost'))
            ->first()->total_cost ?? 0;

        $todayProfit = $todayRevenue - $todayCost;
        $totalStaff = \App\Models\User::count();
        $lowStockItems = \App\Models\Inventory::whereColumn('quantity', '<', 'min_stock_level')->get();

        $topSelling = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_qty'))
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')->take(5)->get();

        return view('admin.dashboard', compact(
            'todayRevenue', 'todayProfit', 'todayOrdersCount', 
            'topSelling', 'recentOrders', 'lowStockItems', 
            'totalStaff', 'startStr', 'endStr', 'weeklyRevenueData', 'days'
        ));

    } else {
        // 4. Staff Specific Logic
        $menuPreview = \App\Models\Product::where('is_active', true)->take(6)->get();

        // Added 'weeklyRevenueData' and 'days' here so the view doesn't crash
        return view('admin.dashboard', compact(
            'todayOrdersCount', 'recentOrders', 'menuPreview', 'startStr', 'endStr', 'weeklyRevenueData', 'days'
        ));
    }
}
public function resetPassword(Request $request, User $user) {
    $request->validate(['password' => 'required|min:8|confirmed']);
    
    $user->update([
        'password' => Hash::make($request->password)
    ]);

    return back()->with('success', 'Password reset successfully!');
}
}