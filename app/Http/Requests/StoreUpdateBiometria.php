<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateBiometria extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'peso' => 'required|numeric|gt:0|digits_between:1,5',
            'quantidade' => 'required|numeric|gt:0|digits_between:1,3',
            'data' => 'required|date|date_format:Y-m-d',
            'observacao' => 'required|max:512',
            'viveiro_id' => 'required|integer|exists:viveiros,id',
        ];
        
        
    }
}