<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PhoneNumberCreateRequest
 * @package App\Http\Requests
 * Class that runs validation for requests that are trying to create a PhoneNumber record
 */
class PhoneNumberCreateRequest extends FormRequest
{
    /**
     * @return array
     * Rules for validation
     */
    public function rules()
    {
        return [
            'number' => 'required|numeric|unique:numbers,number',
            'name' => 'required',
            'contact_id' => 'required|integer'
        ];
    }
}
