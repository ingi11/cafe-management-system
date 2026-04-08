<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    
    protected $fillable = [
    'name', 
    'price', 
    'category_id', 
    'image', 
    'stock_quantity',   // ADD THIS
    'alert_threshold',  // ADD THIS
    'status',           // ADD THIS (so your deactivate button works)
    'is_available'      // ADD THIS
];
    // THIS IS THE CORRECT PLACE:
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // app/Models/Product.php
// app/Models/Product.php
public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relationship to Category (Ensure this exists too!)
     */
    
public function inventory()
{
    // This tells Laravel: "Find the row in the inventories table 
    // where 'item_name' matches this product's 'name'"
    return $this->hasOne(Inventory::class, 'item_name', 'name');
}

}