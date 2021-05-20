<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorStoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        if ($this->getMethod() == 'POST') {
            $rules = [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:vendors',
                'mobile' => 'required',
                'password' => 'required|min:6|confirmed',
            ];
        }else{
            $rules = [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:vendors,email,' . $this->vendor->id,
            ];
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.unique'  => 'This email is already registered.'
        ];
    }
}
