<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentNoticeScheduleRequest extends FormRequest
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
        $rules = [];
        for($month=1;$month<=12;$month++){
            if(isset($this->request->all()['payment_notice_date_'.$month])) $rules['payment_notice_date_'.$month] = 'required';
        }

        return $rules;

    }

    public function messages() 
    {
        $msgArr = [];
        for($month=1;$month<=12;$month++){
            if(isset($this->request->all()['payment_notice_date_'.$month])) $msgArr['payment_notice_date_'.$month.'.required'] = setMessage('name.required');
        }

		return $msgArr;
	}
}
