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
            'donatorEmail' => 'required|string|email|max:100',
            'donatorName' => 'required|string|max:100',
            'amount' => 'required|numeric',
            'challengerNick' => 'required|string|max:255'
        ];
    }
}
