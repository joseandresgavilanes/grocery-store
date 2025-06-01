<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
     'name',
     'image',
     'custom'   
    ];

    protected $casts = [
        'custom' => 'array'
    ];

    public function getImageUrlAttribute()
    {
        if (Storage::disk('public')->exists("categories/$this->image")) {
            return asset("storage/courses/$this->image");
        } else {
            return asset("storage/categories/category_no_image.png");
        }
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
