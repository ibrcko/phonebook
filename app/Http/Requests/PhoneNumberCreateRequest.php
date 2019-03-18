<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class PhoneNumberCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'number' => 'required|unique:numbers,number',
            'name' => 'required',
            'contact_id' => 'required|integer'
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
