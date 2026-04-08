<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
    'user_id',          // ADD THIS
    'customer_name',    // ADD THIS
    'order_number', 
    'total_amount', 
    'status', 
    'payment_method'
];
    public function items()
{
    return $this->hasMany(OrderItem::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}

}
