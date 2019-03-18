<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PhoneNumberUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'number' => 'required_without_all:name,label|unique:numbers,number',
            'name' => 'required_without_all:label,number',
            'label' => 'required_without_all:number,name',
        ];
    }

    public function validate($input)
    {
        $validator = Validator::make($input, $this->rules());

        if ($validator->fails()) {
            return $this->sendError('Validation Error: ' . $validator->errors());
        }

        $data = $validator->getData();

        return $data;
    }
}
