<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class JournalAdjustmentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        // $rules= [];

        return [

            'collector_statement_no' => 'required',
            'remarks'                => 'required',
            'income_code.*'          => 'required',
            // 'debit.*'                => 'required',
            // 'credit.*'               => 'required',
            'checker'                => 'required',
        ];

        // if(isset($this->request->all()['income_code'] ))
        // {
        //     foreach($this->request->all()['income_code'] as $key => $value)
        //     {
        //         if(isset($this->request->all()['income_code'][$key]) != 0)
        //         {
        //             $rules['debit.'.$key] = 'required';
        //             $rules['credit.'.$key] = 'required';
        //         }
        //     }
        // }

        // return $rules;
    }

    public function messages()
    {
        // $messages= [];

		return [

            'collector_statement_no.required'   =>  setMessage('collector_statement_no.required'),
            'remarks.required'                  =>  setMessage('remarks.required'),
            'income_code.*.required'            =>  '',
            // 'debit.*.required'                  => ' ',
            // 'credit.*.required'                 => ' ',
            'checker.required'                  =>  setMessage('checker.required'),
		];

        // if(isset($this->request->all()['income_code'] ))
        // {
        //     foreach($this->request->all()['income_code'] as $key => $value)
        //     {
        //         if(isset($this->request->all()['income_code'][$key]) != '')
        //         {
        //             $messages['debit.'.$key.'.required']   = setMessage('debit.required');
        //             $messages['credit.'.$key.'.required']    = setMessage('credit.required');
        //         }
        //     }
        // }

        // return $messages;

	}
}
