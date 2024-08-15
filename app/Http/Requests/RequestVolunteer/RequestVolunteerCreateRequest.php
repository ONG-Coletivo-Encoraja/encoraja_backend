<?php

namespace App\Http\Requests\RequestVolunteer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestVolunteerCreateRequest extends FormRequest
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
            'availability' => 'required|string',
            'course_experience' => 'required|string',
            'how_know' => 'required|string',
            'expectations' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'availability.required' => 'O campo disponibilidade é obrigatório.',
            'availability.string' => 'O campo disponibilidade deve ser uma string.',
            'course_experience.required' => 'O campo experiência de curso é obrigatório.',
            'course_experience.string' => 'O campo experiência de curso deve ser uma string.',
            'how_know.required' => 'O campo como soube é obrigatório.',
            'how_know.string' => 'O campo como soube deve ser uma string.',
            'expectations.required' => 'O campo expectativas é obrigatório.',
            'expectations.string' => 'O campo expectativas deve ser uma string.',
        ];
    }
}
