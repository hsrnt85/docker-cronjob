<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfficerUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'officer_category' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'officer_category.required' =>  setMessage('officer_category.required'),
        ];
    }

}

