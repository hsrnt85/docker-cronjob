<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenaltyRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];

        if($this->request->get("ic_numb"))
        {
            $rules['ic_numb']      = ['required', 'digits:12'];
        }

        $rules['penalty_date']     = 'required';
        $rules['remarks']          = 'required';
        $rules['penalty_amount']   = 'required';

        return $rules;

    }

    public function messages()
    {
        $messages = [];

        if($this->request->get("ic_numb"))
        {
            $messages['ic_numb.required']         = setMessage('ic_numb.required');
            $messages['ic_numb.digits']           = setMessage('ic_numb.digits');
        }

        $messages['penalty_date.required']        = setMessage('penalty_date.required');
        $messages['remarks.required']             = setMessage('remarks.required');
        $messages['penalty_amount.required']      = setMessage('penalty_amount.required');

        return $messages;
	}
}
