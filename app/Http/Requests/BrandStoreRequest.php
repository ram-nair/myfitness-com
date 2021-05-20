<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandStoreRequest extends FormRequest
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
                'description' => 'nullable',
                'image'=> 'nullable|mimes:jpg,jpeg,png|max:1000'
            ];
        }else{
            $rules = [
                'name' => 'required|max:120',
                'description' => 'nullable',
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'image.mimes'  => 'Please Upload only Image Formats.'
        ];
    }
}
