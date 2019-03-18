<?php

namespace App\Http\Controllers\API;


use App\Contact;
use App\Http\Requests\PhoneNumberCreateRequest;
use App\Http\Requests\PhoneNumberUpdateRequest;
use App\PhoneNumber;
use App\Repository\PhoneNumberRepository;

class PhoneNumberController extends BaseController
{
    public function index(PhoneNumberRepository $repo)
    {
        $phoneNumbers = $repo->getAll();

        if ($phoneNumbers->isEmpty()) {
            return $this->sendError('No contacts found.');
        }

        return $this->sendResponse($phoneNumbers->toArray(), 'Contacts retrieved successfully.');
    }

    public function store(PhoneNumberCreateRequest $request, PhoneNumberRepository $repo)
    {
        $input = $request->all();

        $data = $request->validate($input);

        $phoneNumber = $repo->create($data);

        return $this->sendResponse($phoneNumber->toArray(), 'Phone number created successfully.');
    }

    public function update(PhoneNumberUpdateRequest $request, PhoneNumberRepository $repo, PhoneNumber $phoneNumber)
    {
        $input = $request->all();

        $data = $request->validate($input);

        $contact = $repo->update($data, $phoneNumber);

        return $this->sendResponse($contact->toArray(), 'Phone number updated successfully.');
    }

    public function show(ContactRepository $repo, $id)
    {
        $contact = $repo->find($id);

        if (is_null($contact)) {
            return $this->sendError('Contact not found.');
        }

        return $this->sendResponse($contact->toArray(), 'Contact retrieved successfully.');
    }

    public function destroy(PhoneNumberRepository $repo, PhoneNumber $phoneNumber)
    {
        $contact = $repo->delete($phoneNumber);

        return $this->sendResponse($contact->toArray(), 'Phone number deleted successfully.');
    }


}
