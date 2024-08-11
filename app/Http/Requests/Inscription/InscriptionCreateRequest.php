<?php

namespace App\Http\Requests\Inscription;

use App\Models\Event;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InscriptionCreateRequest extends FormRequest
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
        $eventId = $this->input('event_id');
        $event = Event::find($eventId); 
        $proofRule = ($event && ($event->price == 0 || $event->price === null)) ? 'nullable' : 'required|string';

        return [
            'event_id' => 'required|exists:events,id',
            'proof' => $proofRule,
        ];
    }


    public function messages(): array
    {
        return [
            'event_id.required' => 'O campo evento é obrigatório.',
            'event_id.exists' => 'O evento selecionado não existe.',
            'proof.required' => 'O comprovante é obrigatório para eventos pagos.',
            'proof.string' => 'O comprovante deve ser uma string.',
        ];
    }
}
