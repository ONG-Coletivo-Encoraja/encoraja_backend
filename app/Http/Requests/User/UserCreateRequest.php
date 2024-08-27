<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserCreateRequest extends FormRequest
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
        $userId = $this->route('user');

        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . ($userId ? $userId : 'NULL'),
            'password' => 'required|string|min:6|max:15',
            'cpf' => 'required|string|size:14|unique:users,cpf,' . ($userId ? $userId : 'NULL'),
            'date_birthday' => 'required|date',
            'ethnicity' => 'required|string|in:white,black,mixed,asian,other',
            'gender' => 'required|string|in:male,female,prefer not say',
            'image_term' => 'required|boolean',
            'data_term' => 'required|boolean',
            'phone' => 'required|string|max:14',
            'street' => 'required|string|max:100',
            'number'=> 'required|string|max:8',
            'neighbourhood' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'zip_code' => 'required|string|max:9',
        ];
    }


    public function messages(): array 
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.max' => 'O nome deve ter no máximo 100 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'A senha deve ser um texto.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.max' => 'A senha deve ter no máximo 15 caracteres.',
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.size' => 'O CPF deve ter 14 caracteres.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'date_birthday.required' => 'O campo data de nascimento é obrigatório.',
            'date_birthday.date' => 'A data de nascimento deve ser uma data válida.',
            'ethnicity.required' => 'O campo etnia é obrigatório.',
            'ethnicity.in' => 'Etnia inválida.',
            'gender.required' => 'O campo gênero é obrigatório.',
            'gender.in' => 'Gênero inválido.',
            'image_term.required' => 'O campo termo de imagem é obrigatório.',
            'image_term.boolean' => 'O termo de imagem deve ser um valor booleano.',
            'data_term.required' => 'O campo termo de dados é obrigatório.',
            'data_term.boolean' => 'O termo de dados deve ser um valor booleano.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.string' => 'O telefone deve ser um texto.',
            'phone.max' => 'O telefone deve ter no máximo 14 caracteres.',
            'street.required' => 'O campo rua é obrigatório.',
            'street.string' => 'A rua deve ser um texto.',
            'street.max' => 'A rua deve ter no máximo 100 caracteres.',
            'number.required' => 'O campo número é obrigatório.',
            'number.string' => 'O número deve ser um texto.',
            'number.max' => 'O número deve ter no máximo 8 caracteres.',
            'neighbourhood.required' => 'O campo bairro é obrigatório.',
            'neighbourhood.string' => 'O bairro deve ser um texto.',
            'neighbourhood.max' => 'O bairro deve ter no máximo 50 caracteres.',
            'city.required' => 'O campo cidade é obrigatório.',
            'city.string' => 'A cidade deve ser um texto.',
            'city.max' => 'A cidade deve ter no máximo 50 caracteres.',
            'zip_code.required' => 'O campo CEP é obrigatório.',
            'zip_code.string' => 'O CEP deve ser um texto.',
            'zip_code.max' => 'O CEP deve ter no máximo 9 caracteres.',
        ];
    }
}
