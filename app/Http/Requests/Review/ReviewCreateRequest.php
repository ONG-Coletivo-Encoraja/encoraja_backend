<?php

namespace App\Http\Requests\Review;

use App\Models\Event;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

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
            'event_id.required' => 'O campo de evento é obrigatório.',
            'event_id.exists' => 'O evento selecionado é inválido.',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::find(Auth::user()->id);
            $eventId = $this->input('event_id');

            if (!$user->inscriptions()->where('event_id', $eventId)->exists()) {
                $validator->errors()->add('user_not_enrolled', 'O usuário não está inscrito no evento selecionado.');
            }

            $event = Event::find($eventId);
            if ($event && $event->status !== 'Active') {
                $validator->errors()->add('event_not_completed', 'O evento selecionado não está concluído.');
            }
        });
    }
}
