<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneNumberCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'number' => 'required|numeric|', /*unique:numbers,number',*/
            'name' => 'required',
            'contact_id' => 'required|integer'
        ];
    }
}
