<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationReviewPostRequest extends FormRequest
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
        $rules = [
          
        ];
        // $rules = [
        //     'application_status' => 'required',
        //     'officer_approval' => 'required'
        // ];
        // if(isset($this->request->all()['application_status'])){
        //     if($this->request->all()['application_status']==4)//ditolak
        //     {
        //         $rules = [
        //             'remarks' => 'required'
        //         ];
        //     }
        // }
        // if(isset($this->request->all()['application_status']))
        // {
        //     foreach($this->request->all()['application_status'] as $key => $val) {
        //         $rules['quantity.' . $key] = 'required';
        //         //$rules['responsibility.' . $key] = 'required';
        //     }
        // }

        return $rules;
    }

    public function messages() {
        $messages = [
           
        ];
		// $messages = [
        //     'application_status.required' => setMessage('application_status.required'),
        //     'officer_approval.required' => setMessage('officer_approval.required'),
        // ];

        // if(isset($this->request->all()['application_status'])){
        //     if($this->request->all()['application_status']==4)//ditolak
        //     {
        //         $messages = [
        //             'remarks.required' => setMessage('remarks.required'),
        //         ];
        //     }
        // }
        return $messages;
	}
}
