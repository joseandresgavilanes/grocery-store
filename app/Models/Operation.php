<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'card_id',
        'order_id',
        'type',
        'amount',
        'date',
        'payment_reference',
        'custom',
    ];

    protected $casts = [
        'amount'            => 'float',
        'date'              => 'datetime',
        'custom'            => 'array',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
