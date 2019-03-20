<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneNumberUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'number' => 'required_without_all:name,label|number|unique:numbers,number',
            'name' => 'required_without_all:label,number',
            'label' => 'required_without_all:number,name',
        ];
    }
}
