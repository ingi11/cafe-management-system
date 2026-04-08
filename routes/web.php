<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;



// Change it to this:
Route::get('/', function () {
    return view('welcome'); 
});

require __DIR__.'/auth.php';


// ===============================
// AUTHENTICATED USERS
// ===============================
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard (role-based inside controller)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');


    // ===============================
    // ADMIN + CASHIER (SHARED ACCESS)
    // ===============================
    Route::prefix('admin')->name('admin.')->group(function () {

        // Products (view only for cashier, full for admin handled by middleware later)
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');

        // Orders (cashier + admin)
        Route::resource('orders', OrderController::class)->only([
            'index', 'create', 'store'
        ]);

        // Inventory & Categories (read-only for cashier)
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    });


    // ===============================
    // ADMIN ONLY
    // ===============================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/products/inactive', [ProductController::class, 'inactiveIndex'])->name('products.inactive');
        // Full Product Management
        Route::resource('products', ProductController::class)->except(['index']);

        Route::patch('/products/{product}/deactivate', 
            [ProductController::class, 'deactivate']
        )->name('products.deactivate');
        // Inside the Admin Only group
        

        // Staff Management
        Route::resource('users', UserController::class);

        // Inventory & Categories (full access)
        Route::resource('inventory', InventoryController::class)->except(['index']);
        Route::resource('categories', CategoryController::class)->except(['index']);

        // Reports
        Route::get('/report', [OrderController::class, 'report'])->name('report');
        Route::resource('users', UserController::class); // Handles Create/Delete
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset');


        // Using resource is cleaner, but if you want manual control:
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    // Changed PATCH to PUT/PATCH to match standard form submissions
    Route::match(['put', 'patch'], '/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    });
});