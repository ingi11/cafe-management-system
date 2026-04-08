<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    // These are the fields we allow to be saved
    protected $fillable = [
        'product_id', 
        'inventory_id', 
        'quantity_required'
    ];

    /**
     * Link back to the Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Link to the Inventory item (Milk, Beans, etc.)
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}