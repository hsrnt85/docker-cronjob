<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'start_date'            => 'required',
            'monitoring_officer_id' => 'required',
            'remarks'               => 'required',
            'maintenance_status'     =>  'required',

		];
    }

    public function messages()
    {
		return [

            'start_date.required'            =>  setMessage('start_date.required'),
            'monitoring_officer_id.required' =>  setMessage('monitoring_officer_id.required'),
            'remarks.required'               =>  setMessage('remarks.required'),
            'maintenance_status.required'    =>  setMessage('maintenance_status.required'),
		];
	}
}
