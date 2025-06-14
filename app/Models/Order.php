<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'member_id',
        'status',
        'date',
        'total_items',
        'shipping_cost',   
        'total',
        'nif',
        'delivery_address',
        'pdf_receipt',     
        'cancel_reason',   
        'custom',
    ];

    protected $casts = [
        'date'            => 'date',
        'total_items'     => 'float',
        'shipping_costs'  => 'float', 
        'total'           => 'float',
        'custom'          => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function items()
    {
        return $this->hasMany(ItemsOrder::class);
    }
}
