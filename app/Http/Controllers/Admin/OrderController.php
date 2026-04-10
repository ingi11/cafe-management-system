<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // In OrderController@create

    public function index()
    {
        $orders = Order::with('items.product')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
{
    // 1. Get all categories so we can build the filter tabs
    $categories = Category::all();

    // 2. Get all products (you can also use with('category') if needed)
    $products = Product::where('status', 'active')->get();

    // 3. Pass both to the view
    return view('admin.orders.create', compact('products', 'categories'));
}

    // public function store(Request $request)
    // {
    //     return DB::transaction(function () use ($request) {
    //         $totalAmount = 0;
            
    //         // 1. Create the base Order first
    //         $order = Order::create([
    //             'customer_name' => $request->customer_name ?? 'Guest',
    //             'total_amount' => 0, // We will update this in a second
    //             'status' => 'pending',
    //             'payment_method' => $request->payment_method,
    //             'order_number' => 'ORD-' . time()
    //         ]);

    //         // 2. Loop through items to handle customizations and pricing
    //         foreach ($request->items as $productId => $data) {
    //             if ($data['quantity'] > 0) {
    //                 $sugar = $data['sugar'] ?? '100%';
    //                 $ice = $data['ice'] ?? 'Normal';
    //                 $addons = $data['addons'] ?? []; 

    //                 // Build the note for the barista
    //                 $note = "Sugar: $sugar, Ice: $ice";
    //                 if (!empty($addons)) {
    //                     $note .= " + " . implode(', ', $addons);
    //                 }

    //                 // Calculate price with add-ons (+$0.50 each)
    //                 $extraCost = count($addons) * 0.50;
    //                 $finalItemPrice = $data['price'] + $extraCost;
                    
    //                 $subtotal = $data['quantity'] * $finalItemPrice;
    //                 $totalAmount += $subtotal;

    //                 // Save the item to the order
    //                 $order->items()->create([
    //                     'product_id' => $productId,
    //                     'quantity' => $data['quantity'],
    //                     'price' => $finalItemPrice,
    //                     'notes' => $note,
    //                 ]);
    //             }
    //         }

    //         // 3. Update the final total
    //         $order->update(['total_amount' => $totalAmount]);

    //         return redirect()->route('orders.index')->with('success', 'New order placed!');
    //     });
    // }

//     public function store(Request $request)
// {
//     return DB::transaction(function () use ($request) {
//         $totalAmount = 0;
        
//         // 1. Create the Order first
//         $order = Order::create([
//             'user_id' => auth()->id(),
//             'customer_name' => $request->customer_name ?? 'Guest',
//             'status' => 'completed',
//             'payment_method' => $request->payment_method,
//             'order_number' => 'ORD-' . time(),
//             'total_amount' => 0 // Will update this in a second
//         ]);

//         foreach ($request->items as $productId => $item) {
//             if ($item['quantity'] > 0) {
//                 $product = Product::find($productId);
//                 $subtotal = $product->price * $item['quantity'];
//                 $totalAmount += $subtotal;

//                 // 2. Create the Order Item
//                 $order->items()->create([
//                     'product_id' => $productId,
//                     'quantity' => $item['quantity'],
//                     'price' => $product->price,
//                 ]);

//                 // 3. THE KEY STEP: Automatically remove from Inventory
//                 // We assume your Product is linked to an Inventory item
//                 $inventory = Inventory::where('item_name', $product->name)->first();
//                 if ($inventory) {
//                     $inventory->decrement('quantity', $item['quantity']);
//                 }
//             }
//         }

//         // 4. Update the final total
//         $order->update(['total_amount' => $totalAmount]);

//         return redirect()->route('admin.orders.create')->with('success', 'Stock Sale Completed!');
//     });
// }

// public function store(Request $request)
// {
//     return DB::transaction(function () use ($request) {
//         // 1. Create the Order
//         $order = Order::create([
//             'user_id' => auth()->id(),
//             'customer_name' => $request->customer_name,
//             'total_amount' => 0, // Calculated below
//             'user_id' => auth()->id(),
//             'status' => 'completed'
//         ]);

//         $grandTotal = 0;

//         foreach ($request->items as $productId => $details) {
//             // Inside the foreach ($request->items as $productId => $details) loop:

//             $product = Product::findOrFail($productId);
//             $qty = $details['quantity'];

//             // 1. Update the Product's own stock (for your Dashboard)
//             if ($product->stock_quantity < $qty) {
//                 throw new \Exception("Not enough stock for {$product->name}!");
//             }
//             $product->decrement('stock_quantity', $qty);

//             // 2. Keep your Recipe/Inventory logic IF you are tracking ingredients (like milk/beans)
//             $inventory = Inventory::where('item_name', $product->name)->first();
//             if ($inventory) {
//                 $inventory->decrement('quantity', $qty);
//             }
//             if ($details['quantity'] > 0) {
//                 $product = Product::findOrFail($productId);
//                 $qty = $details['quantity'];

//                 // 2. Add to Order Items
//                 $order->items()->create([
//                     'product_id' => $product->id,
//                     'quantity' => $qty,
//                     'price' => $product->price
//                 ]);

//                 // 3. THE CONNECTION: Subtract from Inventory
//                 $inventory = Inventory::where('item_name', $product->name)->first();
                
//                 if ($inventory) {
//                     // Safety Check: Don't sell more than we have
//                     if ($inventory->quantity < $qty) {
//                         throw new \Exception("Not enough stock for {$product->name}!");
//                     }
                    
//                     $inventory->decrement('quantity', $qty);
//                 }

//                 $grandTotal += ($product->price * $qty);
//             }
//         }

//         $order->update(['total_amount' => $grandTotal]);

//         return redirect()->route('admin.orders.create')->with('success', 'Order Complete & Stock Updated!');
//     });
// }

public function store(Request $request)
{
    return DB::transaction(function () use ($request) {
        // 1. Create the Order
        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'total_amount' => 0, // Will update after loop
            'status' => 'completed',
            'order_number' => 'ORD-' . time(),
            'payment_method' => $request->payment_method ?? 'Cash',
        ]);

        $grandTotal = 0;

        foreach ($request->items as $productId => $details) {
            $qty = (int) $details['quantity'];
            
            if ($qty > 0) {
                $product = Product::findOrFail($productId);

                // --- 2. STOCK CHECK & DECREMENT (Product Table) ---
                if ($product->stock_quantity < $qty) {
                    throw new \Exception("Not enough stock for {$product->name}!");
                }
                $product->decrement('stock_quantity', $qty);

                // --- 3. INVENTORY CHECK & DECREMENT (Optional Ingredients) ---
                $inventory = Inventory::where('item_name', $product->name)->first();
                if ($inventory) {
                    if ($inventory->quantity >= $qty) {
                        $inventory->decrement('quantity', $qty);
                    }
                }

                // --- 4. CREATE ORDER ITEM ---
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price
                ]);

                $grandTotal += ($product->price * $qty);
            }
        }

        // 5. Update final total
        $order->update(['total_amount' => $grandTotal]);

        return redirect()->route('admin.orders.create')->with('success', 'Order Complete & Stock Updated!');
    });
}


    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('items.product.recipes.inventory')->findOrFail($id);
        
        // Only subtract stock if the order is moving to 'completed'
        if ($request->status == 'completed' && $order->status != 'completed') {
            foreach ($order->items as $item) {
                if ($item->product && $item->product->recipes) {
                    foreach ($item->product->recipes as $recipe) {
                        $inventoryItem = $recipe->inventory;
                        
                        if ($inventoryItem) {
                            $totalNeeded = $recipe->quantity_required * $item->quantity;
                            $inventoryItem->decrement('quantity', $totalNeeded);
                        }
                    }
                }
            }
        }

        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated and stock adjusted!');
    }


public function report(Request $request)
{
    // 1. Get dates from request (Default to today)
    $startStr = $request->get('start_date', \Carbon\Carbon::today()->format('Y-m-d'));
    $endStr = $request->get('end_date', \Carbon\Carbon::today()->format('Y-m-d'));

    $startDate = \Carbon\Carbon::parse($startStr)->startOfDay(); 
    $endDate = \Carbon\Carbon::parse($endStr)->endOfDay();
    $filter = $request->get('filter', 'custom');

    // 2. Filter Revenue and Orders
    $totalRevenue = \App\Models\Order::where('status', 'completed')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->sum('total_amount');
    
    $totalOrders = \App\Models\Order::whereBetween('created_at', [$startDate, $endDate])->count();
    
    // This is the total number of all items sold (for the top summary card)
    $totalItemsSold = \App\Models\OrderItem::whereHas('order', function($q) use ($startDate, $endDate) {
        $q->where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate]);
    })->sum('quantity');

    // 3. Get specific product sales for the grid (for the @foreach loop)
    $stockSold = \App\Models\Product::withSum(['orderItems' => function($q) use ($startDate, $endDate) {
            $q->whereHas('order', function($o) use ($startDate, $endDate) {
                $o->where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate]);
            });
        }], 'quantity')
        ->get()
        ->where('order_items_sum_quantity', '>', 0);

    // 4. Staff Performance (Filtered)
    $cashierSales = \App\Models\User::withCount(['orders' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }])
        ->withSum(['orders' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        }], 'total_amount')
        ->get();

    // 5. Sales by Category (Filtered)
    // 4. Sales by Category (Filtered with proper calculation)
$categorySales = \App\Models\Category::with(['products.orderItems' => function($q) use ($startDate, $endDate) {
        $q->whereHas('order', function($o) use ($startDate, $endDate) {
            $o->where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate]);
        });
    }])->get()->map(function($category) {
        // We manually calculate the total for each category object
        $category->total_qty = 0;
        $category->total_earned = 0;

        foreach($category->products as $product) {
            $qty = $product->orderItems->sum('quantity');
            $category->total_qty += $qty;
            $category->total_earned += ($qty * $product->price);
        }
        return $category;
    });

    return view('admin.orders.report', compact(
        'totalRevenue', 
        'totalOrders', 
        'totalItemsSold', 
        'stockSold', 
        'cashierSales', 
        'categorySales', 
        'startStr', 
        'endStr',
        'filter'
    ));
}

}