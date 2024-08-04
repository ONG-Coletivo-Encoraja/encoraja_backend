<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($userId ? $userId : 'NULL'),
            'password' => 'required|min:6',
            'cpf' => 'required|string|size:14|unique:users,cpf,' . ($userId ? $userId : 'NULL'),
            'date_birthday' => 'required|date',
            'race' => 'required|string',
            'gender' => 'required|string',
            'image_term' => 'required|boolean',
            'data_term' => 'required|boolean',
            'phone' => 'required|string',

            'street' => 'required|string',
            'number'=> 'required|string',
            'neighbourhood' => 'required|string',
            'city' => 'required|string',
            'zip_code' => 'required|string',
        ];
    }


    public function messages(): array 
    {
        return [
            'name.required' => 'Campo de nome é obrigatório',
            'name.string' => 'O nome deve ser uma string',
            'name.max' => 'O nome deve ter no máximo 255 caracteres',
            'email.required' => 'Campo de email é obrigatório',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'Este email já está cadastrado',
            'password.required' => 'Campo de senha é obrigatório',
            'password.min' => "A senha deve ter pelo menos :min caracteres",
            'cpf.required' => 'Campo de CPF é obrigatório',
            'cpf.size' => 'O CPF deve ter 14 caracteres',
            'cpf.unique' => 'Este CPF já está cadastrado',
            'date_birthday.required' => 'Campo de data de nascimento é obrigatório',
            'date_birthday.date' => 'A data de nascimento deve ser uma data válida',
            'race.required' => 'Campo de raça é obrigatório',
            'gender.required' => 'Campo de gênero é obrigatório',
            'image_term.boolean' => 'O termo de imagem deve ser um valor booleano',
            'data_term.boolean' => 'O termo de dados deve ser um valor booleano',
            'phone.required' => 'Campo de celular é obrigatório',
        ];
    }
}
