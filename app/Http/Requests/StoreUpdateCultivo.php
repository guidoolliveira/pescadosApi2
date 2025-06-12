<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateCultivo extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'viveiro_id' => ['required', 'exists:viveiros,id'],
            'data_inicio' => ['required', 'date'],
            'quantidade_camarao' => ['required', 'numeric', 'min:1', 'max:999999999'],
            'data_fim' => ['nullable', 'date', 'after_or_equal:data_inicio'],
        ];
    }
}
