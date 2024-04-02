<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IncomeAccountCodeRequest extends FormRequest
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
        $flag_outstanding = isset($this->flag_outstanding) ? $this->flag_outstanding : 1;//dd($flag_outstanding);
        $services_status = isset($this->services_status) ?? "'".ArrayToString($this->services_status)."'";

        $rules = [];
        $rules['salary_deduction_code'] = ['required'];
        $rules['general_income_code'] = ['required'];
        $rules['ispeks_account_code'] = ['required'];
        $rules['income_code'] = ['required',
                Rule::unique('income_account_code')->where(function ($query){
                    $query->where(['ispeks_account_code'=> $this->ispeks_account_code,'data_status'=> 1]);
                    if(isset($this->id)){
                        $query = $query->where('id', '<>', $this->id);//edit page
                    }
                    return $query;
                })
            ];
        $rules['income_code_description'] = ['required'];
        $rules['account_type'] = ['required',
                Rule::unique('income_account_code','account_type_id')->where(function ($query) use ($flag_outstanding, $services_status){
                    $query->where(['flag_outstanding'=> $flag_outstanding, 'data_status'=> 1]);
                    if(isset($this->id)){
                        $query->where('id', '<>', $this->id);//edit page
                    }
                    if(isset($this->payment_category)){
                        $query->where('payment_category_id', $this->payment_category);
                    }
                    if(isset($this->services_status)){
                        $query->where('services_status_id', $services_status);
                    }
                    return $query;
                })
            ];
        $rules['payment_category'] = ['required',
                Rule::unique('income_account_code','payment_category_id')->where(function ($query) use ($flag_outstanding, $services_status){
                    $query->where(['flag_outstanding'=> $flag_outstanding, 'data_status'=> 1]);
                    if(isset($this->id)){
                        $query->where('id', '<>', $this->id);//edit page
                    }
                    if(isset($this->account_type)){
                        $query->where('account_type_id', $this->account_type);
                    }
                    if(isset($this->services_status)){
                        $query->where('services_status_id', $services_status);
                    }
                    return $query;
                })
            ];

        return $rules;
    }

    public function messages()
    {
		return [
			'salary_deduction_code.required' => setMessage('salary_deduction_code.required'),
			'ispeks_account_code.required' => setMessage('ispeks_account_code.required'),
            'ispeks_account_code.unique' =>  setMessage('ispeks_account_code.unique'),
            'general_income_code.required' => setMessage('general_income_code.required'),
            'income_code.required' => setMessage('income_code.required'),
            'income_code.unique' => setMessage('income_code.unique'),
            'income_code_description.required' => setMessage('income_code_description.required'),
            'account_type.required' => setMessage('account_type.required'),
            'account_type.unique' =>  setMessage('account_type.unique'),
            'payment_category.required' => setMessage('payment_category.required'),
            'payment_category.unique' =>  setMessage('payment_category.unique'),
            'services_status_id.unique' =>  setMessage('services_status_id.unique'),
		];
	}
}
