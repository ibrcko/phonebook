<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PhoneNumberCreateRequest;
use App\Http\Requests\PhoneNumberUpdateRequest;
use App\PhoneNumber;
use App\Repository\PhoneNumberRepository;

/**
 * Class PhoneNumberController
 * @package App\Http\Controllers\API
 * Controller for processing requests to routes: api/phone-numbers
 */
class PhoneNumberController extends BaseController
{
    /**
     * @param PhoneNumberRepository $repo
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for retrieving all PhoneNumber records
     */
    public function index(PhoneNumberRepository $repo)
    {
        $phoneNumbers = $repo->getAll();

        if ($phoneNumbers->isEmpty()) {
            return $this->sendError('No phone numbers found.');
        }

        return $this->sendResponse($phoneNumbers->toArray(), 'Contacts retrieved successfully.');
    }

    /**
     * @param PhoneNumberCreateRequest $request
     * @param PhoneNumberRepository $repo
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for creating a PhoneNumber record
     */
    public function store(PhoneNumberCreateRequest $request, PhoneNumberRepository $repo)
    {
        $input = $request->all();

        $phoneNumber = $repo->create($input);

        return $this->sendResponse($phoneNumber->toArray(), 'Phone number created successfully.');
    }

    /**
     * @param PhoneNumberUpdateRequest $request
     * @param PhoneNumberRepository $repo
     * @param PhoneNumber $phoneNumber
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for updating a PhoneNumber record
     */
    public function update(PhoneNumberUpdateRequest $request, PhoneNumberRepository $repo, PhoneNumber $phoneNumber)
    {
        $input = $request->all();

        $phoneNumberResult = $repo->update($input, $phoneNumber);

        return $this->sendResponse($phoneNumberResult->toArray(), 'Phone number updated successfully.');
    }

    /**
     * @param PhoneNumberRepository $repo
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for retrieving s single PhoneNumber record
     */
    public function show(PhoneNumberRepository $repo, $id)
    {
        $phoneNumber = $repo->find($id);

        if (is_null($phoneNumber)) {
            return $this->sendError('Phone number not found.');
        }

        return $this->sendResponse($phoneNumber->toArray(), 'Phone number retrieved successfully.');
    }

    /**
     * @param PhoneNumberRepository $repo
     * @param PhoneNumber $phoneNumber
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for deleting a single Contact record
     */
    public function destroy(PhoneNumberRepository $repo, PhoneNumber $phoneNumber)
    {
        $contact = $repo->delete($phoneNumber);

        return $this->sendResponse($contact->toArray(), 'Phone number deleted successfully.');
    }


}
