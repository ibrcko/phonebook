<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ContactCreateRequest extends FormRequest
{
    public function rules() {
        return [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:contacts,email',
                'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'favourite' => 'boolean',

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
