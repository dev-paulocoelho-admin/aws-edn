<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConsultaCepRequest extends FormRequest
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
            'cep' => [
                'required',
                'string',
                'regex:/^(\d{8}|\d{5}-\d{3})$/', // Exatamente 8 dígitos OU 5-3 com hífen
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'cep.required' => 'O campo CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser uma string.',
            'cep.regex' => 'O CEP deve conter 8 dígitos ou estar no formato 00000-000.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'cep' => 'CEP',
        ];
    }
}
