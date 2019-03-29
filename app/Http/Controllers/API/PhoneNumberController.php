<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PhoneNumberCreateRequest;
use App\Http\Requests\PhoneNumberUpdateRequest;
use App\PhoneNumber;
use App\Repository\PhoneNumberRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PhoneNumberController
 * @package App\Http\Controllers\API
 * Controller for processing requests to routes: api/phone-numbers
 */
class PhoneNumberController extends BaseController
{
    /**
     * @param $request
     * @return JsonResponse|mixed
     * Method that checks if user_id parameter is in request /api/phone-numbers?user_id=
     * If there is no user_id as parameter, the method checks if user is logged in on frontend
     * Method returns either user_id or JsonResponse for unauthorized access
     */
    private function authenticateApiRequest($request)
    {
        $form = $request->all();
        if (!array_key_exists('user_id', $form)) {
            if(!is_null(auth()->user())) {
                $userId = auth()->user()->getAuthIdentifier();
            } else {
                return $this->sendError('Unauthorized.', 'Unauthorized access.', 403);
            }
        } else {
            $userId = $form['user_id'];
        }

        return $userId;
    }

    /**
     * @param PhoneNumberRepository $repo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for retrieving all PhoneNumber records
     */
    public function index(PhoneNumberRepository $repo, Request $request)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

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
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

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
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

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
    public function show(PhoneNumberRepository $repo, $id, Request $request)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

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
    public function destroy(PhoneNumberRepository $repo, PhoneNumber $phoneNumber, Request $request)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        $contact = $repo->delete($phoneNumber);

        return $this->sendResponse($contact->toArray(), 'Phone number deleted successfully.');
    }


}
