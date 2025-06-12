<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateEstoque extends FormRequest
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
        'product_id' => 'required|exists:products,id',
        'quantidade' => 'required|numeric|min:1',
        'lote'=> 'required|string|max:50',
        'validade'=> 'required|date_format:Y-m-d|after:today',
        ];
        

        
        
    }
    public function messages()
{
    return [
        'validade.after' => 'A data de validade deve ser uma data futura.',
    ];
}

}
