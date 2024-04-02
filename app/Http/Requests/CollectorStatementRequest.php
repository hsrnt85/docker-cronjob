<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectorStatementRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $rules = [];

        if($this->request->get("page") == "new")
        {
            $rules['date_from']   = 'required';
            $rules['date_to']     = 'required';
            $rules['checker']     = 'required';
        }

        if($this->request->get("page") == "edit") // aduan selesai
        {
            if($this->request->get("current_status") == 1)
            $rules['checker']   = 'required';
        }


        return $rules;
    }

    public function messages()
    {
        $messages = [];

        if ($this->request->get("page") == "new")
        {
            $messages['date_from.required']         = setMessage('remarks.required');
            $messages['date_to.required']           = setMessage('remarks.required');
            $messages['checker.required']           = setMessage('remarks.required');
        }
        else if($this->request->get("page") == "edit") // aduan selesai & pemantauan berulang
        {
            $messages['monitoring_status']      = setMessage('monitoring_status.required');
        }

        return $messages;
    }
}
