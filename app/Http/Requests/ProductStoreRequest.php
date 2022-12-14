<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
        return [
            'name'=>'required|string',
            
            'brand'=>'nullable|string',
            'category'=>'nullable|string',
            'sale_price'=>'required|numeric',
            'stock'=>'nullable',
        ];
    }
    public function messages()
    {
        return [
            'name.required'=>"အမည်ထည့်ရန်လိုအပ်သည်",
            'name.string' =>'စာသားဖြစ်ရန်လိုအပ်သည်',
            'sale_price.required'=>"ဈေးထည့်ရန်လိုအပ်သည်",
            
            'brand.required'=>"brand ထည့်ရန်လိုအပ်သည်",
        ];
    }
}
