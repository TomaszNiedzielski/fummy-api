<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'fullName' => 'required|string|between:5,50',
            'email' => 'required|string|email|max:255|unique:users',
            'nick' => 'required|string|max:30|unique:users',
            'password' => 'required|string|between:6,50'
        ];
    }

    /**
     * Return error messages in response.
     * 
     * @return array 
     */
    public function messages()
    {
        return [
            'fullName.required' => 'Imię i nazwisko jest wymagane.',
            'fullName.between' => 'Imię i nazwisko musi mieć od 5 do 50 znaków.',
            'email.required' => 'Adres e-mail jest wymagany.',
            'email.email' => 'Podaj poprawny adres e-mail.',
            'email.max' => 'Podany adres e-mail jest za długi.',
            'nick.required' => 'Nick jest wymagany.',
            'nick.max' => 'Podany nick jest za długi.',
            'password.required' => 'Hasło jest wymagane.',
            'password.between' => 'Hasło musi mieć od 6 do 50 znaków.'
        ];
    }
}