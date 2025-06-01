<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    public $timestamps = false;
     
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount_applied',
    ];

    protected $casts = [
        'quantity'         => 'integer',
        'unit_price'       => 'float',
        'discount_applied' => 'float',
    ];

    // RELACIONES
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
