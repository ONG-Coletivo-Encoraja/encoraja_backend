<?php

namespace App\Http\Requests\ReportAdmin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReportAdminRequest extends FormRequest
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
            'qtt_person' => 'required|integer',
            'description' => 'required|string',
            'results' => 'required|string',
            'observation' => 'required|string',
            'event_id' => 'required|integer|exists:relates_events,id'
        ];
    }

    public function messages(): array
    {
        return [
            'qtt_person.required' => 'O campo quantidade de pessoas é obrigatório.',
            'qtt_person.integer' => 'O campo quantidade de pessoas deve ser um número inteiro.',
            'description.required' => 'O campo descrição é obrigatório.',
            'description.string' => 'O campo descrição deve ser uma string.',
            'results.required' => 'O campo resultados é obrigatório.',
            'results.string' => 'O campo resultados deve ser uma string.',
            'observation.required' => 'O campo observação é obrigatório.',
            'observation.string' => 'O campo observação deve ser uma string.',
            'event_id.required' => 'O campo ID do evento relacionado é obrigatório.',
            'event_id.integer' => 'O campo ID do evento relacionado deve ser um número inteiro.',
            'event_id.exists' => 'O ID do evento relacionado fornecido não existe.',
        ];
    }
}
