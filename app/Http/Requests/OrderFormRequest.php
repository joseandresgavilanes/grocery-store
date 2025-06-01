<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_id'       => 'required|integer|exists:users,id',
            'status'          => 'required|in:pending,completed,canceled',
            'date'            => 'required|date',
            'total_items'     => 'required|numeric|min:0',
            'shipping_costs'  => 'required|numeric|min:0',
            'total'           => 'required|numeric|min:0',
            'nif'             => 'nullable|string',
            'delivery_address'=> 'required|string',
            'custom'          => 'nullable|array',
        ];
    }
}