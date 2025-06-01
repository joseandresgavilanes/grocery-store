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
        'shipping_costs',
        'total',
        'nif',
        'delivery_address',
        'custom',
    ];

    //pdf_receipt
    //cancel_reason

    protected $casts = [
        'date'           => 'date',
        'total_items'    => 'float',
        'shipping_costs' => 'float',
        'total'          => 'float',
        'custom'         => 'array',
    ];

    // RELACIONES
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function items()
    {
        return $this->hasMany(ItemOrder::class);
    }
}