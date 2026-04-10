<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address'];

    /**
     * Define the relationship: A supplier has many inventory items.
     */
    public function inventories(): HasMany
    {
        
        return $this->hasMany(Inventory::class);
    }
}