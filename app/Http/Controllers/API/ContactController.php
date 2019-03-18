<?php

namespace App\Http\Controllers\API;

use App\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;

class ContactController extends BaseController
{
    public function index()
    {
        $contacts = Contact::all();

        return $this->sendResponse($contacts->toArray(), 'Contacts retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error: ' . $validator->errors());
        }

        $contact = Contact::create($input);

        if (!empty($input['profile_photo'])) {
            $profilePhotoPath = $this->imageUpload($request->file('profile_photo'));

            $contact->profile_photo = $profilePhotoPath;
        }

        $contact->save();

        return $this->sendResponse($contact->toArray(), 'Contact created successfully.');
    }

    public function show($id)
    {
        $contact = Contact::find($id);

        if (is_null($contact)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse($contact->toArray(), 'Contact retrieved successfully.');
    }

    public function edit(Request $request, Contact $contact)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            $this->sendError('Validation Error', $validator->errors());
        }

        $contact->first_name = $input['first_name'];
        $contact->last_name = $input['last_name'];
        $contact->email = $input['email'];

        $contact->save();

        return $this->sendResponse($contact->toArray(), 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return $this->sendResponse($contact->toArray(), 'Contact deleted successfully.');
    }

    public function imageUpload($profilePhoto) {

        $name = time() . '.' . $profilePhoto->getClientOriginalExtension();

        $path = $profilePhoto->storeAs('profile-photos', $name);

        return $path;
    }
}
