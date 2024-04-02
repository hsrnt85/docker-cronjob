<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceOfficerRequest extends FormRequest
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

    public function rules()
    {
        $rules = [];
        if(isset($this->officer)){
            $rules['officer'] = ['required',
                    Rule::unique('finance_officer','users_id')->where(function ($query){
                        $query->where(['users_id'=> $this->officer,
                                        'district_id'=> districtId(),
                                        'data_status'=> 1
                        ]);

                        return $query;
                    })
                ];
        }
        return $rules;

    }

    public function messages()
    {

        if(isset($this->officer)){
            $messages = [
                'officer.required' => setMessage('officer.required')
            ];
        }

        $messages = [
            'officer.unique' =>  setMessage('officer.unique'),
			'officer_category.required' => setMessage('officer_category.required'),
        ];

        return $messages;
	}
}
