<?php
// app/Http/Requests/CardFormRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Deja que el controlador autorice por policy si hace falta
        return true;
    }

    public function rules(): array
    {
        return [
            // El id DEBE coincidir con un usuario y ser único en cards
            'id'              => 'required|integer|exists:users,id|unique:cards,id',
            // Número de tarjeta de 6 dígitos entre 100000 y 999999, único
            'card_number'     => 'required|integer|between:100000,999999|unique:cards,card_number',
            // Saldo inicial (puede ser cero)
            'balance'         => 'required|numeric|min:0',
        ];
    }
}