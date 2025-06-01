<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplyOrderFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id'              => 'required|integer|exists:products,id',
            'registered_by_user_id'   => 'required|integer|exists:users,id',
            'status'                  => 'required|in:requested,completed',
            'quantity'                => 'required|integer|min:1',
        ];
    }
}