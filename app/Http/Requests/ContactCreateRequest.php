<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * Class ContactCreateRequest
 * @package App\Http\Requests
 * Class that runs validation for requests that are trying to create a Contact record
 */
class ContactCreateRequest extends FormRequest
{
    /**
     * @return array
     * Rules for validation
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:contacts,email',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favourite' => 'boolean',
        ];
    }
}
