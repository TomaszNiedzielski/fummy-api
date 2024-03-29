<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'name' => 'max:50',
            'email' => 'required|string|email|max:255',
            'instructions' => 'max:500',
            'offerId' => 'required|numeric',
            'forWhom' => 'required|string',
            'fromWhoName' => 'string|max:50',
            'forWhomName' => 'string|max:50',
            'occasion' => 'required|string|max:256',
        ];
    }
}
