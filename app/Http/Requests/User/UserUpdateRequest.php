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
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email,' . $this->route('user'),
            'password' => 'nullable|string|min:6|max:15',
            'cpf' => 'nullable|string|max:14|unique:users,cpf,' . $this->route('user'),
            'date_birthday' => 'nullable|date',
            'ethnicity' => 'nullable|string|in:white,black,mixed,asian,other',
            'gender' => 'nullable|string|in:male,female,prefer not say',
            'phone' => 'nullable|string|max:14',
            'availability' => 'nullable|string|max:100',
            'course_experience' => 'nullable|string',
            'how_know' => 'nullable|string',
            'expectations' => 'nullable|string',
            'street' => 'nullable|string|max:100',
            'number'=> 'nullable|string|max:8',
            'neighbourhood' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'zip_code' => 'nullable|string|max:9',
            'type' => 'nullable|string'
        ];
    }

    public function messages(): array 
    {
        return [
            'name.string' => 'O nome deve ser um texto.',
            'name.max' => 'O nome deve ter no máximo 100 caracteres.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.string' => 'A senha deve ser um texto.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.max' => 'A senha deve ter no máximo 15 caracteres.',
            'cpf.string' => 'O CPF deve ser um texto.',
            'cpf.size' => 'O CPF deve ter 14 caracteres.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'date_birthday.date' => 'A data de nascimento deve ser uma data válida.',
            'ethnicity.in' => 'Etnia inválida.',
            'gender.in' => 'Gênero inválido.',
            'phone.string' => 'O telefone deve ser um texto.',
            'phone.max' => 'O telefone deve ter no máximo 14 caracteres.',
            'availability.string' => 'A disponibilidade deve ser um texto.',
            'availability.max' => 'A disponibilidade deve ter no máximo 100 caracteres.',
            'course_experience.string' => 'A experiência de curso deve ser um texto.',
            'how_know.string' => 'Como conheceu deve ser um texto.',
            'expectations.string' => 'As expectativas devem ser um texto.',
            'street.string' => 'A rua deve ser um texto.',
            'street.max' => 'A rua deve ter no máximo 100 caracteres.',
            'number.string' => 'O número deve ser um texto.',
            'number.max' => 'O número deve ter no máximo 8 caracteres.',
            'neighbourhood.string' => 'O bairro deve ser um texto.',
            'neighbourhood.max' => 'O bairro deve ter no máximo 50 caracteres.',
            'city.string' => 'A cidade deve ser um texto.',
            'city.max' => 'A cidade deve ter no máximo 50 caracteres.',
            'zip_code.string' => 'O CEP deve ser um texto.',
            'zip_code.max' => 'O CEP deve ter no máximo 9 caracteres.',
            'type.string' => 'O tipo deve ser um texto.',
        ];
    }
}
