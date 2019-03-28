<?php

namespace App\Http\Controllers\API;

use App\Contact;
use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Repository\ContactRepository;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;

/**
 * Class ContactController
 * @package App\Http\Controllers\API
 * Controller for processing requests to routes: api/contacts
 */
class ContactController extends BaseController
{
    /**
     * @param ContactRepository $repo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for searching a Contact records by given query, on first_name and last_name, per user
     */
    public function search(ContactRepository $repo, Request $request)
    {
        $form = $request->all();

        if (!array_key_exists('query', $form)) {

            $contactsResponse = $this->index($repo, $request);

            return $contactsResponse;

        } else {
            if (!array_key_exists('user_id', $form)) {
                $userId = auth()->user()->getAuthIdentifier();
            } else {
                $userId = $form['user_id'];
            }

            $contacts = $repo->search($form['query'], $userId);
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
        $form = $request->all();
        if (!array_key_exists('user_id', $form)) {
            $userId = auth()->user()->getAuthIdentifier();
        } else {
            $userId = $form['user_id'];
        }

        $contacts = $repo->getAll($userId);

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

        if (!array_key_exists('query', $form)) {

            $contactsResponse = $this->favourite($repo, $request);

            return $contactsResponse;

        } else {

            $keyword = $form['query'];
            if (!array_key_exists('user_id', $form)) {
                $userId = auth()->user()->getAuthIdentifier();
            } else {
                $userId = $form['user_id'];
            }

            $contacts = $repo->searchFavourites($keyword, $userId);

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
        $form = $request->all();
        if (!array_key_exists('user_id', $form)) {
            $userId = auth()->user()->getAuthIdentifier();
        } else {
            $userId = $form['user_id'];
        }

        $contacts = $repo->getAllFavourites($userId);

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
        $input = $request->all();

        $contact = $repo->create($input);

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
        $input = $request->all();

        $contact = $repo->update($input, $contact);

        return $this->sendResponse($contact->toArray(), 'Contact updated successfully.');
    }

    /**
     * @param ContactRepository $repo
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for retrieving s single Contact record
     */
    public function show(ContactRepository $repo, $id)
    {
        $contact = $repo->find($id);

        if (is_null($contact)) {
            return $this->sendError('Contact not found.');
        }

        return $this->sendResponse($contact->toArray(), 'Contact retrieved successfully.');
    }

    /**
     * @param ContactRepository $repo
     * @param Contact $contact
     * @return \Illuminate\Http\JsonResponse
     * Method that processes request for deleting a single Contact record
     */
    public function destroy(ContactRepository $repo, Contact $contact)
    {
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
        $input = $request->all();

        $repo->updateImage($input, $contact);

        return $this->sendResponse($contact->toArray(), 'Profile photo updated.');
    }

}
