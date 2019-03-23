<?php

namespace App\Http\Controllers\API;

use App\Contact;
use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Repository\ContactRepository;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
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

    public function store(ContactCreateRequest $request, ContactRepository $repo)
    {
        $input = $request->all();

        $contact = $repo->create($input);

        return $this->sendResponse($contact->toArray(), 'Contact created successfully.');
    }

    public function update(ContactUpdateRequest $request, ContactRepository $repo, Contact $contact)
    {
        $input = $request->all();

        $contact = $repo->update($input, $contact);

        return $this->sendResponse($contact->toArray(), 'Contact updated successfully.');
    }

    public function show(ContactRepository $repo, $id)
    {
        $contact = $repo->find($id);

        if (is_null($contact)) {
            return $this->sendError('Contact not found.');
        }

        return $this->sendResponse($contact->toArray(), 'Contact retrieved successfully.');
    }

    public function destroy(ContactRepository $repo, Contact $contact)
    {
        $contact = $repo->delete($contact);

        return $this->sendResponse($contact->toArray(), 'Contact deleted successfully.');
    }

    public function updateImage(ContactUpdateRequest $request, ContactRepository $repo, Contact $contact)
    {
        $input = $request->all();

        $repo->updateImage($input, $contact);

        return $this->sendResponse($contact->toArray(), 'Profile photo updated.');
    }

}
