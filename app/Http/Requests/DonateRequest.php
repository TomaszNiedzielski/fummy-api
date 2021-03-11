<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonateRequest extends FormRequest
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
            'donatorEmail' => 'required|string|email|max:255',
            'donatorName' => 'required|string|max:50',
            'message' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'challengerNick' => 'required|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'donatorEmail.required' => 'Adres e-mail jest wymagany.',
            'donatorEmail.email' => 'Podaj poprawny adres e-mail.',
            'donatorEmail.max' => 'Podany adres e-mail jest za długi.',
            'donatorName.required' => 'Imię jest wymagane.',
            'donatorName.max' => 'Imię musi mieć od 3 do 50 znaków',
            'message.max' => 'Maksymalna długość wiadomości to 255 znaków.',
            'amount.required' => 'Podaj kwotę, którą chcesz wysłać.',
            'amount.numeric' => 'Kwota musi być liczbą.',
        ];
    }
}
