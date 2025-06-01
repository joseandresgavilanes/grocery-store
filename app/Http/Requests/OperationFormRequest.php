<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_id'           => 'required|integer|exists:cards,id',
            'type'              => 'required|in:credit,debit',
            'amount'            => 'required|numeric|min:0',
            'date'              => 'required|date',
            'payment_reference' => 'nullable|string',
            'custom'            => 'nullable|array',
            'order_id'          => 'nullable|integer|exists:orders,id',
            'debit_type'        => 'nullable|in:order,membership_fee',
            'credit_type'       => 'nullable|in:payment,order_cancellation',
            'payment_type'      => 'nullable|in:Visa,PayPal,MB WAY',
        ];
    }
}