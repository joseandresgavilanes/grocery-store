<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    // Si tu tabla no se llama "supply_orders", descomenta y ajusta:
    // protected $table = 'supply_orders';

    protected $fillable = [
        'product_id',
        'registered_by_user_id',
        'status',   // 'requested' | 'completed'
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Relación con el producto que se repone.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Usuario (empleado o administrador) que registró el pedido.
     */
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }
}