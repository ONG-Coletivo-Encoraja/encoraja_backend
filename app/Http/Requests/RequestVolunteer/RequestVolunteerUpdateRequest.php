<?php

namespace App\Http\Requests\RequestVolunteer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestVolunteerUpdateRequest extends FormRequest
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
            'availability' => 'nullable|string',
            'course_experience' => 'nullable|string',
            'how_know' => 'nullable|string',
            'expectations' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'availability.string' => 'O campo disponibilidade deve ser uma string.',
            'course_experience.string' => 'O campo experiência de curso deve ser uma string.',
            'how_know.string' => 'O campo como soube deve ser uma string.',
            'expectations.string' => 'O campo expectativas deve ser uma string.',
        ];
    }
}
