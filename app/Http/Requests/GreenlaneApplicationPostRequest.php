<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GreenlaneApplicationPostRequest extends FormRequest
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

        $rules['quarter_category'] = 'required';
        $rules['phone_no_office'] = 'required';
        $rules['fax_no_office'] = 'required';
        // $rules['office_email'] = 'required';
        $rules['email_office'] = 'required';
       
        // Spouse
        if($this->request->get("is_spouse_work") == 1) 
        {
            $rules['spouse_office_address_1'] = 'required';
            $rules['spouse_office_address_2'] = 'required';
            $rules['spouse_position'] = 'required';
            $rules['spouse_salary'] = 'required';
        }  
        $rules['spouse_phone_no'] = 'required';

        // Children
        if(isset($this->request->all()['child']))
        {
            foreach($this->request->all()['child'] as $key => $val) { 
                $rules['child_ic_document.' . $key] = 'required|mimes:jpg,jpeg,pdf,png'; 
            }
        }

        if($this->request->get("is_epnj_user") == 1) 
        {
            $rules['user_epnj_address_1']   = 'required';
            $rules['user_epnj_address_2']   = 'required';
            $rules['user_epnj_mukim']       = 'required';
        }


        if($this->request->get("is_epnj_spouse") == 1) 
        {
            $rules['spouse_epnj_address_1'] = 'required';
            $rules['spouse_epnj_address_2'] = 'required';
            $rules['spouse_epnj_mukim']     = 'required';

        }

        $rules['review_document'] = 'nullable|mimes:jpg,jpeg,pdf,png';

        // Dokumen Sokongan
        if(isset($this->request->all()['document']))
        {
            // foreach($this->request->all()['child'] as $key => $val) { 
            //     $rules['child_ic_document.' . $key] = 'required'; 
            // }
            $rules['document.*'] = 'mimes:jpg,jpeg,pdf,png';
        }

        $rules['disclaimer'] = 'required|min:1';

        return $rules;
    }

    public function messages() {

        $messages = [];

        $messages['quarter_category.required'] = '1. Sila pilih kategori kuarters';
        $messages['phone_no_office.required'] = '3. Sila isi nombor telefon pejabat';
        $messages['fax_no_office.required'] = '3. Sila isi nombor fax pejabat';
        $messages['email_office.required'] = '3. Sila isi emel pejabat';
        
        // Spouse
        $messages['is_spouse_work.required'] = '4. Sila pilih adakah pasangan bekerja';
        if($this->request->get("is_spouse_work") == 1) 
        {
            $messages['spouse_office_address_1.required'] = '4. Sila isi alamat pejabat pasangan';
            $messages['spouse_office_address_2.required'] = '4. Sila isi alamat pejabat pasangan';
            $messages['spouse_position.required'] = '4. Sila isi jawatan pasangan';
            $messages['spouse_salary.required'] = '4. Sila isi gaji pasangan';
        }  
        $messages['spouse_phone_no.required'] = '4. Sila isi nombor telefon pasangan';


        // Children
        if(isset($this->request->all()['child']))
        {
            foreach($this->request->all()['child'] as $key => $val) { 
                $messages['child_ic_document.' . $key . '.required'] = '5. Sila muat naik salinan kad pengenalan'; 
            }
        }

        if($this->request->get("is_epnj_user") == 1) 
        {
            $messages['user_epnj_address_1.required'] = '7. Sila isi alamat rumah PNJ';
            $messages['user_epnj_address_2.required'] = '7. Sila isi alamat rumah PNJ';
            $messages['user_epnj_mukim.required']     = '7. Sila isi mukim rumah PNJ';

        }


        if($this->request->get("is_epnj_spouse") == 1) 
        {
            $messages['spouse_epnj_address_1.required'] = '7. Sila isi alamat rumah PNJ';
            $messages['spouse_epnj_address_2.required'] = '7. Sila isi alamat rumah PNJ';
            $messages['user_epnj_mukim.required']       = '7. Sila isi mukim rumah PNJ';

        }

        $messages['disclaimer.required'] = '9. Sila klik pengakuan';
        $messages['disclaimer.min'] = '9. Sila klik pengakuan';

        return $messages;
	}
}
