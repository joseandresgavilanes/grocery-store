<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'id',           
        'card_number',
        'balance',
    ];

    protected $casts = [
        'balance' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'card_id');
    }
}
