<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplaintMonitoringPostRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $rules = [];

        if($this->request->get("complaint_status") == 3) // aduan selesai
        {
            $rules['remarks']   = 'required';
            $rules['complaint_status']   = 'required';
        }
        else if($this->request->get("monitoring_status") == 3) // aduan selesai
        {
            $rules['monitoring_status']   = 'required';
        }


        return $rules;
    }

    public function messages() {

        $messages = [];

        if($this->request->get("complaint_status") == 3)  // aduan selesai
        {
            $messages['remarks.required']           = setMessage('remarks.required');
        }
        else if($this->request->get("monitoring_status") <= 2) // aduan selesai & pemantauan berulang
        {
            $messages['monitoring_status']      = setMessage('monitoring_status.required');
        }

        return $messages;
	}
}
