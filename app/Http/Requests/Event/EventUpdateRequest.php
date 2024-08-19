<?php

namespace App\Http\Requests\Event;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventUpdateRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date|after_or_equal:today',
            'time' => 'nullable|date_format:H:i',
            'modality' => 'nullable|in:Presential,Hybrid,Remote',
            'status' => 'nullable|in:Active,Inactive,Pending,Finished',
            'type' => 'nullable|in:Course,Workshop,Lecture',
            'target_audience' => 'nullable|string|max:255',
            'vacancies' => 'nullable|integer|min:1',
            'social_vacancies' => 'nullable|integer|min:0',
            'regular_vacancies' => 'nullable|integer|min:0',
            'material' => 'nullable|string',
            'interest_area' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'workload' => 'nullable|integer|min:1',
            'owner' => 'nullable|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O campo nome não pode exceder 255 caracteres.',
            'description.required' => 'O campo descrição é obrigatório.',
            'date.required' => 'O campo data é obrigatório.',
            'date.date' => 'O campo data deve ser uma data válida.',
            'time.required' => 'O campo horário é obrigatório.',
            'time.date_format' => 'O campo horário deve estar no formato HH:mm.',
            'modality.required' => 'O campo modalidade é obrigatório.',
            'modality.in' => 'O campo modalidade deve ser um dos seguintes valores: Presencial, Híbrido ou Remoto.',
            'status.required' => 'O campo status é obrigatório.',
            'status.in' => 'O campo status deve ser um dos seguintes valores: Ativo, Inativo ou Pendente.',
            'type.required' => 'O campo tipo é obrigatório.',
            'type.in' => 'O campo tipo deve ser um dos seguintes valores: Curso, Workshop ou Palestra.',
            'target_audience.required' => 'O campo público-alvo é obrigatório.',
            'target_audience.string' => 'O campo público-alvo deve ser um texto.',
            'target_audience.max' => 'O campo público-alvo não pode exceder 255 caracteres.',
            'vacancies.required' => 'O campo vagas é obrigatório.',
            'vacancies.integer' => 'O campo vagas deve ser um número inteiro.',
            'vacancies.min' => 'O campo vagas deve ser no mínimo 1.',
            'social_vacancies.integer' => 'O campo vagas sociais deve ser um número inteiro.',
            'social_vacancies.min' => 'O campo vagas sociais não pode ser negativo.',
            'regular_vacancies.integer' => 'O campo vagas regulares deve ser um número inteiro.',
            'regular_vacancies.min' => 'O campo vagas regulares não pode ser negativo.',
            'material.string' => 'O campo material deve ser um texto.',
            'interest_area.required' => 'O campo área de interesse é obrigatório.',
            'interest_area.string' => 'O campo área de interesse deve ser um texto.',
            'interest_area.max' => 'O campo área de interesse não pode exceder 255 caracteres.',
            'price.required' => 'O campo preço é obrigatório.',
            'price.numeric' => 'O campo preço deve ser um número.',
            'price.min' => 'O campo preço não pode ser negativo.',
            'workload.required' => 'O campo carga horária é obrigatório.',
            'workload.integer' => 'O campo carga horária deve ser um número inteiro.',
            'workload.min' => 'O campo carga horária deve ser no mínimo 1.',
            'owner.required' => 'O campo proprietário é obrigatório.',
            'owner.integer' => 'O campo proprietário deve ser um número inteiro.',
            'date.after_or_equal' => 'A data do evento não pode ser anterior ao dia de hoje.',
        ];
    }
}
