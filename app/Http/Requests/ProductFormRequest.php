<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'       => 'required|integer|exists:categories,id',
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'description'       => 'required|string',
            'photo'             => 'nullable|string',
            'discount_min_qty'  => 'nullable|integer|min:1',
            'discount'          => 'nullable|numeric|min:0',
            'stock_lower_limit' => 'required|integer|min:0',
            'stock_upper_limit' => 'required|integer|min:stock_lower_limit',
            'custom'            => 'nullable|array',
        ];
    }
}