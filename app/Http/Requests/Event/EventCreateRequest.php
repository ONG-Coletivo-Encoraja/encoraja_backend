<?php namespace App\Http\Requests\Event;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Altere para true se o usuário estiver autorizado a fazer essa solicitação
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'modality' => 'required|in:Presential,Hybrid,Remote',
            'status' => 'in:Active,Inactive,Pending',
            'type' => 'required|in:Course,Workshop,Lecture',
            'target_audience' => 'required|string|max:255',
            'vacancies' => 'required|integer|min:0',
            'social_vacancies' => 'nullable|integer|min:0',
            'regular_vacancies' => 'nullable|integer|min:0',
            'material' => 'nullable|string',
            'interest_area' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'workload' => 'required|float|min:0',
        ];
    }
}
