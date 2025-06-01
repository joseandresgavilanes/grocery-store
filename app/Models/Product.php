<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'description',
        'photo',
        'discount_min_qty',
        'discount',
        'stock_lower_limit',
        'stock_upper_limit',
        'custom',
    ];

    protected $casts = [
        'price'             => 'float',
        'stock'             => 'integer',
        'discount_min_qty'  => 'integer',
        'discount'          => 'float',
        'stock_lower_limit' => 'integer',
        'stock_upper_limit' => 'integer',
        'custom'            => 'array',
    ];


    // RELACIONES
    public function category()
    {
       return $this->belongsTo(Category::class);
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function supplyOrderItems()
    {
        return $this->hasMany(SupplyOrderItem::class);
    }
}
