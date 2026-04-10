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
    'stock_quantity',   
    'alert_threshold',  
    'status',           
    'is_available'      
];

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    
public function inventory()
{
   
    return $this->hasOne(Inventory::class, 'item_name', 'name');
}

}