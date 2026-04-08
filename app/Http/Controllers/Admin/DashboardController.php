<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Category; 
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Revenue from completed orders
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // 2. Orders today
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();

        // 3. Low Stock Alerts (Min stock level logic is great for CADT projects!)
        $lowStockItems = Inventory::whereColumn('quantity', '<', 'min_stock_level')->get();

        // 4. Menu Stats
        $totalProducts = Product::count();
        
        // Match this with the 'is_active' column we added earlier!
        $outOfStockMenu = Product::where('is_active', false)->count();

        // 5. Chart Data (Items per category)
        $categorySales = Category::withCount('products')->get()->map(function ($category) {
            return [
                'name' => $category->name,
                'count' => $category->products_count
            ];
        });

        // 6. Recent Transactions
        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'todayOrders', 
            'lowStockItems', 
            'totalProducts',
            'recentOrders',
            'categorySales',
            'outOfStockMenu'
        ));
    }
}