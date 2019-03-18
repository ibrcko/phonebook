<?php

namespace App\Http\Controllers\API;

use App\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContactController extends BaseController
{
    public function index()
    {
        $contacts = Contact::all();
        if ($contacts->isEmpty()) {
            return $this->sendError('No contacts found.');
        }

        return $this->sendResponse($contacts->toArray(), 'Contacts retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:contacts,email',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favourite' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error: ' . $validator->errors());
        }

        $contact = Contact::create($input);

        if (!empty($input['profile_photo'])) {
            $profilePhotoPath = $this->imageUpload($request);

            $contact->profile_photo = $profilePhotoPath;
        }

        $contact->save();

        return $this->sendResponse($contact->toArray(), 'Contact created successfully.');
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        if (is_null($contact)) {
            return $this->sendError('Contact not found.');
        }

        return $this->sendResponse($contact->toArray(), 'Contact retrieved successfully.');
    }

    public function update(Request $request, Contact $contact)
    {
        $updatable = [
            'first_name',
            'last_name',
            'email',
            'favourite',
        ];

        $input = $request->all();

        $validator = Validator::make($input, [
            'first_name' => 'required_without_all:last_name,email,favourite',
            'last_name' => 'required_without_all:first_name,email,favourite',
            'email' => 'required_without_all:first_name,last_name,favourite|email|unique:contacts,email',
            'favourite' => 'required_without_all:first_name,last_name,email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors());
        }

        foreach ($updatable as $field) {
            if (array_key_exists($field, $input))
                $contact->$field = $input[$field];
        }

        $contact->save();

        return $this->sendResponse($contact->toArray(), 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        if (!is_null($contact->profile_photo)) {
            $path = $contact->profile_photo;

            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        $contact->delete();

        return $this->sendResponse($contact->toArray(), 'Contact deleted successfully.');
    }

    public function imageUpload($request)
    {
        $profilePhoto = $request->file('profile_photo');

        $name = time() . '.' . $profilePhoto->getClientOriginalExtension();

        $path = $profilePhoto->storeAs('profile-photos', $name);

        return $path;
    }

    public function updateImage(Request $request, Contact $contact)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error: ' . $validator->errors());
        }

        if (!empty($input['profile_photo'])) {
            $profilePhotoPath = $this->imageUpload($request);

            $contact->profile_photo = $profilePhotoPath;
        }

        $contact->save();

        return $this->sendResponse($contact->toArray(),'Image updated');
    }
}
