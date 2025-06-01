<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'quantity',
        'reason',
        'user_id',
        'date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'date'     => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}