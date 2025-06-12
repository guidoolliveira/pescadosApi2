<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateUsoDiario extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'viveiro_id' => ['required', 'exists:viveiros,id'],
            'produto_id' => ['required', 'exists:products,id'],
            'data' => ['required', 'date'],
            'quantidade_utilizada' => ['required', 'numeric', 'min:0'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
