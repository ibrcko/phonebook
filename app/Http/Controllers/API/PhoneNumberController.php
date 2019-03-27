<?php

namespace App\Http\Controllers\API;

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

        $phoneNumber = $repo->create($input);

        return $this->sendResponse($phoneNumber->toArray(), 'Phone number created successfully.');
    }

    public function update(PhoneNumberUpdateRequest $request, PhoneNumberRepository $repo, PhoneNumber $phoneNumber)
    {
        $input = $request->all();

        $phoneNumberResult = $repo->update($input, $phoneNumber);

        return $this->sendResponse($phoneNumberResult->toArray(), 'Phone number updated successfully.');
    }

    public function show(PhoneNumberRepository $repo, $id)
    {
        $phoneNumber = $repo->find($id);

        if (is_null($phoneNumber)) {
            return $this->sendError('Phone number not found.');
        }

        return $this->sendResponse($phoneNumber->toArray(), 'Phone number retrieved successfully.');
    }

    public function destroy(PhoneNumberRepository $repo, PhoneNumber $phoneNumber)
    {
        $contact = $repo->delete($phoneNumber);

        return $this->sendResponse($contact->toArray(), 'Phone number deleted successfully.');
    }


}
