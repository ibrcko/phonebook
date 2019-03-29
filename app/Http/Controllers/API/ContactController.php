<?php

namespace App\Http\Controllers\API;

use App\Contact;
use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Repository\ContactRepository;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ContactController
 * @package App\Http\Controllers\API
 * Controller for processing requests to routes: api/contacts
 */
class ContactController extends BaseController
{
    /**
     * @param $request
     * @return JsonResponse|mixed
     * Method that checks if user_id parameter is in request /api/contacts?user_id= or in request body
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
     * @param ContactRepository $repo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for searching a Contact records by given query, on first_name and last_name, per user
     */
    public function search(ContactRepository $repo, Request $request)
    {
        $form = $request->all();

        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        if (!array_key_exists('query', $form)) {

            $contactsResponse = $this->index($repo, $request);

            return $contactsResponse;

        } else {
            $contacts = $repo->search($form['query'], $userAuthentication);
        }

        if ($contacts->isEmpty()) {
            return $this->sendError('No contacts found.');
        }

        return $this->sendResponse($contacts->toArray(), 'Contacts retrieved successfully.');
    }

    /**
     * @param ContactRepository $repo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for retrieving all Contact records for a given user
     */
    public function index(ContactRepository $repo, Request $request)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        $contacts = $repo->getAll($userAuthentication);

        if ($contacts->isEmpty()) {
            return $this->sendError('No contacts found.');
        }

        return $this->sendResponse($contacts->toArray(), 'Contacts retrieved successfully.');
    }

    /**
     * @param ContactRepository $repo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for searching a favourite Contact records by given query, on first_name and last_name, per user
     */
    public function searchFavourite(ContactRepository $repo, Request $request)
    {
        $form = $request->all();

        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        if (!array_key_exists('query', $form)) {

            $contactsResponse = $this->favourite($repo, $request);

            return $contactsResponse;

        } else {

            $keyword = $form['query'];

            $contacts = $repo->searchFavourites($keyword, $userAuthentication);

        }

        if ($contacts->isEmpty()) {
            return $this->sendError('No contacts found.');
        }

        return $this->sendResponse($contacts->toArray(), 'Contacts retrieved successfully.');
    }

    /**
     * @param ContactRepository $repo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for retrieving all favourite Contact records for a given user
     */
    public function favourite(ContactRepository $repo, Request $request)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        $contacts = $repo->getAllFavourites($userAuthentication);

        if ($contacts->isEmpty()) {
            return $this->sendError('No contacts found.');
        }

        return $this->sendResponse($contacts->toArray(), 'Contacts retrieved successfully.');
    }

    /**
     * @param ContactCreateRequest $request
     * @param ContactRepository $repo
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for creating a Contact record
     */
    public function store(ContactCreateRequest $request, ContactRepository $repo)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        $input = $request->all();

        $contact = $repo->create($input, $userAuthentication);

        return $this->sendResponse($contact->toArray(), 'Contact created successfully.');
    }

    /**
     * @param ContactUpdateRequest $request
     * @param ContactRepository $repo
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for updating a Contact record
     */
    public function update(ContactUpdateRequest $request, ContactRepository $repo, Contact $contact)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        $input = $request->all();

        $contact = $repo->update($input, $contact);

        return $this->sendResponse($contact->toArray(), 'Contact updated successfully.');
    }

    /**
     * @param ContactRepository $repo
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for retrieving s single Contact record
     */
    public function show(ContactRepository $repo, $id, Request $request)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        $contact = $repo->find($id);

        if (is_null($contact)) {
            return $this->sendError('Contact not found.');
        }

        return $this->sendResponse($contact->toArray(), 'Contact retrieved successfully.');
    }

    /**
     * @param ContactRepository $repo
     * @param Contact $contact
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for deleting a single Contact record
     */
    public function destroy(ContactRepository $repo, Contact $contact, Request $request)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        $contact = $repo->delete($contact);

        return $this->sendResponse($contact->toArray(), 'Contact deleted successfully.');
    }

    /**
     * @param ContactUpdateRequest $request
     * @param ContactRepository $repo
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for updating a profile image for Contact
     */
    public function updateImage(ContactUpdateRequest $request, ContactRepository $repo, Contact $contact)
    {
        $userAuthentication = $this->authenticateApiRequest($request);
        if (gettype($userAuthentication) == "object") {
            return $userAuthentication;
        }

        $input = $request->all();

        $repo->updateImage($input, $contact);

        return $this->sendResponse($contact->toArray(), 'Profile photo updated.');
    }

}
