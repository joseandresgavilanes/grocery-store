<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemOrderFormRequest extends FormRequest
{
        public function authorize(): bool
        {
            return true;
        }
    
        public function rules(): array
        {
            return [
                'order_id'         => 'required|integer|exists:orders,id',
                'product_id'       => 'required|integer|exists:products,id',
                'quantity'         => 'required|integer|min:1',
                'unit_price'       => 'required|numeric|min:0',
                'discount_applied' => 'required|numeric|min:0',
            ];
        }
}