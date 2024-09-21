<?php

namespace App\Http\Requests\Review;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReviewCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator) : void 
    {
        throw new HttpResponseException(response()->json([
            'status'=> false,
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
            'rating' => 'required|integer|min:1|max:5',
            'observation' => 'nullable|string|max:1000',
            'recommendation' => 'required|bool',
            'feel_welcomed' => 'required|bool',
            'event_id' => 'required|exists:events,id',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'O campo de avaliação é obrigatório.',
            'rating.integer' => 'O campo de avaliação deve ser um número inteiro.',
            'rating.min' => 'A avaliação mínima é 1.',
            'rating.max' => 'A avaliação máxima é 5.',
            'observation.string' => 'A observação deve ser um texto.',
            'observation.max' => 'A observação não pode ter mais de 1000 caracteres.',
            'recommendation.required' => 'O campo de recomendação é obrigatório.',
            'recommendation.boolean' => 'O campo de recomendação deve ser verdadeiro ou falso.',
            'feel_welcomed.required' => 'O campo de acolhimento é obrigatório.',
            'feel_welcomed.boolean' => 'O campo de acolhimento deve ser verdadeiro ou falso.',
            'event_id.required' => 'O campo de evento é obrigatório.',
            'event_id.exists' => 'O evento selecionado é inválido.',
        ];
    }
}
