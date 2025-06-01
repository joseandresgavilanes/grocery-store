<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'            => 'required|integer|exists:products,id',
            'registered_by_user_id' => 'required|integer|exists:users,id',
            'quantity_changed'      => 'required|integer',
        ];
    }
}