<?php

namespace App\Http\Requests;

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
    
    protected function failedValidation(Validator $validator) : void {
        throw new HttpResponseException(response()->json([
            'status'=> false,
            'errors' => $validator->errors(),
        ], 422));   
        // 422, o servido entende a solicitação mas não pode processar por erro na validação
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. ($userId ? $userId : null),
            'password' => 'required|min:6'
        ];
    }


    public function messages(): array {
        return [
            'name.required'=> 'Campo de nome é obrigatório',
            'email.required'=> 'Campo de email é obrigatório',
            'email.unique'=> 'Este email já está cadastrado',
            'email.email' => 'O email deve ser válido',
            'password.required' => 'Campo de senha é obrigatório',
            'password.min' => "A senha deve ter pelo menos :min caracteres"
        ];
    }
}
