<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractorRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'contractor_name'  => 'required',
            'pic'              =>  'required',
            'address_1'        =>  'required',
            'district_id'      =>  'required',
            'postcode'         =>  'required',
            'phone_no_office'  =>  'required',
            'phone_no_hp'      =>  'required',
            'company_email'    =>  'required',

		];
    }

    public function messages()
    {
		return [

            'contractor_name.required'  =>  setMessage('contractor_name.required'),
            'pic.required'              =>  setMessage('pic.required'),
            'address_1.required'        =>  setMessage('address_1.required'),
            'district_id.required'      =>  setMessage('district_id.required'),
            'postcode.required'         =>  setMessage('postcode.required'),
            'phone_no_office.required'  =>  setMessage('phone_no_office.required'),
            'phone_no_hp.required'      =>  setMessage('phone_no_hp.required'),
            'company_email.required'    =>  setMessage('company_email.required'),
		];
	}
}
