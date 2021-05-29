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
            'nick' => 'required|regex:/^[a-z0-9_]+$/|string|between:3,30|unique:users',
            'password' => 'required|string|between:8,16'
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
            'email.unique' => 'Ten adres e-mail jest zajęty.',
            'nick.required' => 'Nick jest wymagany.',
            'nick.between' => 'Nick musi mieć od 3 do 30 znaków.',
            'nick.unique' => 'Ten nick jest zajęty.',
            'nick.regex' => 'Nick może zawierać jedynie małe litery, cyfry i podkreślenia.',
            'password.required' => 'Hasło jest wymagane.',
            'password.between' => 'Hasło musi mieć od 8 do 16 znaków.'
        ];
    }
}