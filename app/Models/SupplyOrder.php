<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{


    protected $fillable = [
        'product_id',
        'registered_by_user_id',
        'status',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }
}
