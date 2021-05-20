<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductFormRequest extends FormRequest
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
                'name'      =>  'required|max:255',
                'unit_price'  =>  'required|regex:/^\d+(\.\d{1,2})?$/',
                'sku'       =>  'required|unique:products,sku',
            ];
        }else{
            $rules = [
                'name'      =>  'required|max:255',
                'unit_price' =>  'required|regex:/^\d+(\.\d{1,2})?$/',
                'sku'       =>  'required|unique:products,sku,'.$this->id,
            ];
        }
        return $rules;
    }
}
