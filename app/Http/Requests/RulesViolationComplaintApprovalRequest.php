<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RulesViolationComplaintApprovalRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];


        $rules['complaint_status'] = 'required';

        if(isset($this->request->all()['complaint_status']))
        {
            if($this->request->all()['complaint_status'] == 2)    $rules['rejection_reason'] = 'required';
        }


        return $rules;

    }

    public function messages()
    {

        $messages= [];


        $messages['complaint_status.required'] =  setMessage('complaint_status.required');

        if(isset($this->request->all()['complaint_status']))
        {
            if($this->request->all()['complaint_status'] == 2) $messages['rejection_reason.required'] = setMessage('rejection_reason.required');
        }
        return $messages;
	}
}
