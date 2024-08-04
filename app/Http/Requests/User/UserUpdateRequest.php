<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ajuste conforme necessário para sua lógica de autorização
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
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $this->route('user'),
            'password' => 'nullable|string|min:6',
            'cpf' => 'nullable|string|max:14|unique:users,cpf,' . $this->route('user'),
            'date_birthday' => 'nullable|date',
            'race' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
            // 'availability' => 'nullable|boolean',
            // 'course_experience' => 'nullable|string|max:255',
            // 'how_know' => 'nullable|string|max:255',
            // 'expectations' => 'nullable|string|max:255',

            'street' => 'nullable|string',
            'number'=> 'nullable|string',
            'neighbourhood' => 'nullable|string',
            'city' => 'nullable|string',
            'zip_code' => 'nullable|string',

            'type' => 'nullable|string'
        ];
    }

    public function messages(): array 
    {
        return [
            'email.email' => 'O e-mail deve ser válido',
            'email.unique' => 'Este e-mail já está cadastrado',
            'password.min' => 'A senha deve ter pelo menos :min caracteres',
            'cpf.unique' => 'Este CPF já está cadastrado',
            'date_birthday.date' => 'A data de nascimento deve ser uma data válida',
            'availability.boolean' => 'Disponibilidade deve ser um valor booleano',
        ];
    }
}
