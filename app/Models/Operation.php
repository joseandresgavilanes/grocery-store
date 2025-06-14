<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'card_id',           
        'order_id',         
        'type',             
        'value',             
        'date',            
        'debit_type',      
        'credit_type',    
        'payment_type',     
        'payment_reference',
    ];

    protected $casts = [
        'value'           => 'float',
        'date'            => 'date',
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