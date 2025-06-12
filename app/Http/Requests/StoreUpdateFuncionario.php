<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateFuncionario extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation()
    {
        if ($this->has('salario')) {
            $this->merge([
                'salario' => str_replace(['.', ','], ['', '.'], $this->salario)
            ]);
        }
        if ($this->has('telefone')) {
            $telefoneLimpo = preg_replace('/\D/', '', $this->telefone); // remove tudo que não for número
            $this->merge([
                'telefone' => $telefoneLimpo
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255', 
            'telefone' => 'required|phone:BR',
            'salario' => 'required|numeric|min:1|max:99999.99',
            'funcao' => 'required|string|max:255'
        ];
    }
    public function messages()
{
    return [
        'telefone.phone' => 'O número de telefone informado não é válido.',
    ];
}
}
