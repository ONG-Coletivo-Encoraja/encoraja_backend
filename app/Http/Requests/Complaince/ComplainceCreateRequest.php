<?php

namespace App\Http\Requests\Complaince;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ComplainceCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422));
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone_number' => 'nullable|string|max:15',
            'description' => 'required|string',
            'relation' => 'required|string',
            'motivation' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'A descrição é obrigatória.',
            'description.string' => 'A descrição deve ser uma string.',
            'relation.required' => 'A relação é obrigatória.',
            'relation.string' => 'A relação deve ser uma string.',
            'motivation.required' => 'A motivação é obrigatória.',
            'motivation.string' => 'A motivação deve ser uma string.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.email' => 'O email deve ser um endereço de email válido.',
            'phone_number.string' => 'O número de telefone deve ser uma string.',
            'phone_number.max' => 'O número de telefone não pode ter mais de 15 caracteres.',
        ];
    }
}
