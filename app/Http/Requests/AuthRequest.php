<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio para realizar el registro',
            'email.required' => 'El email es obligatorio para realizar el registro',
            'email.email' => 'Por favor ingrese un email valido',
            'email.unique' => 'Este correo ya esta registrador, por favor ingrese uno diferente',
            'password.required' => 'La contraseña es obligatoria para realizar el registro',
            'password.min' => 'La contraseña debe contener minimo 8 caracteres',
        ];
    }
}
