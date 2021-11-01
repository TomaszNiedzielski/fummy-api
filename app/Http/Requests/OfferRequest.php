<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
            'offers.*.title' => 'required|string|between:1,30',
            'offers.*.description' => 'required|string|between:1,255',
            'offers.*.price' => 'required|numeric|min:0',
            'offers.*.currency' => 'required|string|max:3'
        ];
    }
}
