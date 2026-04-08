<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'item_name', 
        'quantity', 
        'unit', 
        'min_stock_level', 
        'category_id',
        'supplier_id',  // Add this!
        'cost_price'    // Add this!
    ];
public function category()
    {
        return $this->belongsTo(Category::class);
    }
public function supplier()
{
    return $this->belongsTo(Supplier::class);
}
}
