<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsShippingCostsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'min_value_threshold' => 'required|numeric|min:0',
            'max_value_threshold' => 'required|numeric|gt:min_value_threshold',
            'shipping_cost'       => 'required|numeric|min:0',
        ];
    }
}