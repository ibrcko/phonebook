<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ContactCreateRequest extends FormRequest
{
    public function rules() {
        return [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',/*|unique:contacts,email*/
                'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'favourite' => 'boolean',
        ];
    }
}
