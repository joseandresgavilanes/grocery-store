<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


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

    public function getImageUrlAttribute(): string
    {
        if ($this->photo && \Storage::disk('public')->exists('products/' . $this->photo)) {
            return asset('storage/products/' . $this->photo);
        }

        return asset('storage/products/product_no_image.png');
    }


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

    public function getDiscountedPriceAttribute(): ?float
    {
        if ($this->discount && $this->discount_min_qty) {
            return round($this->price * (1 - $this->discount), 2);
        }
        return null;
    }

        public function supplyOrders()
    {
        return $this->hasMany(SupplyOrder::class);
    }
}
