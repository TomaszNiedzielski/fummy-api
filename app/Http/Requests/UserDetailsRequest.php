<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDetailsRequest extends FormRequest
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
            'fullName' => 'required|between:5,50',
            'nick' => 'required|regex:/^[a-z0-9_]+$/|string|between:3,30|unique:users,nick,'.auth()->user()->id,
            'bio' => 'max:255',
            'avatar' => 'mimes:jpeg,jpg,png'
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
            'nick.required' => 'Nick jest wymagany.',
            'nick.between' => 'Nick musi mieć od 3 do 30 znaków.',
            'nick.unique' => 'Ten nick jest zajęty.',
            'nick.regex' => 'Nick może zawierać jedynie małe litery, cyfry i podkreślenia.',
            'bio.max' => 'Bio nie może być dłuższe niż 255 znaków.'
        ];
    }
}
