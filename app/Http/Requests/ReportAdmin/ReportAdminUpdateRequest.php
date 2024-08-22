<?php

namespace App\Http\Requests\ReportAdmin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReportAdminUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'qtt_person' => 'nullable|integer',
            'description' => 'nullable|string',
            'results' => 'nullable|string',
            'observation' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'qtt_person.integer' => 'A quantidade de pessoas deve ser um número inteiro.',
            'description.string' => 'A descrição deve ser uma string.',
            'results.string' => 'Os resultados devem ser uma string.',
            'observation.string' => 'A observação deve ser uma string.',
        ];
    }
}
