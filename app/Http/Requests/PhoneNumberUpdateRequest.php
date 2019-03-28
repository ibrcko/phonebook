<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PhoneNumberUpdateRequest
 * @package App\Http\Requests
 * Class that runs validation for requests that are trying to update a PhoneNumber record
 */
class PhoneNumberUpdateRequest extends FormRequest
{
    /**
     * @return array
     * Rules for validation
     */
    public function rules()
    {
        return [
            'number' => 'required_without_all:name,label|numeric|unique:numbers,number',
            'name' => 'required_without_all:label,number',
            'label' => 'required_without_all:number,name',
        ];
    }
}
