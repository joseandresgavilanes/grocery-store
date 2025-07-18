<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemsOrder extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',      
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'float',
        'discount'   => 'float',
        'subtotal'   => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}