<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUpdateRequest extends FormRequest
{
    public function rules() {
        return  [
            'first_name' => 'required_without_all:last_name,email,favourite,profile_photo',
            'last_name' => 'required_without_all:first_name,email,favourite,profile_photo',
            'email' => 'required_without_all:first_name,last_name,favourite,profile_photo|email|unique:contacts,email',
            'favourite' => 'required_without_all:first_name,last_name,email,profile_photo',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
