<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingsShippingCosts extends Model
{

    protected $table = 'settings_shipping_costs';

    protected $fillable = [
        'min_value_threshold',
        'max_value_threshold',
        'shipping_cost',
    ];

    protected $casts = [
        'min_value_threshold' => 'float',
        'max_value_threshold' => 'float',
        'shipping_cost'       => 'float',
    ];
}
