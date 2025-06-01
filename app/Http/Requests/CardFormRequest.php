<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id'           => 'required|integer|exists:users,id|unique:cards,id',
            'card_number'  => 'required|integer|between:100000,999999|unique:cards,card_number',
            'balance'      => 'required|numeric|min:0',
        ];
    }
}