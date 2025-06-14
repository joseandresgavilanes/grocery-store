<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    $userId = $this->user()->id;

    return [
        'name'                         => 'required|string|max:255',
        'email'                        => 'required|email|unique:users,email,' . $userId,
        'password'                     => $this->isMethod('post')
                                                ? 'required|string|min:8|confirmed'
                                                : 'nullable|string|min:8|confirmed',
        'type'                         => 'sometimes|in:member,board,employee,pending_member',
        'blocked'                      => 'sometimes|boolean',
        'gender'                       => 'required|in:M,F',
        'photo'                        => 'nullable|image|max:2048',
        'nif'                          => 'nullable|string',
        'default_delivery_address'     => 'nullable|string',
        'default_payment_type'         => 'nullable|in:Visa,PayPal,MB WAY',
        'default_payment_reference'    => 'nullable|string',
        'custom'                       => 'nullable|array',
    ];
}

}
