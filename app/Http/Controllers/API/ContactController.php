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
    public function index(ContactRepository $repo)
    {
        $userId = auth()->user()->getAuthIdentifier();

        $contacts = $repo->getAll($userId);

        if ($contacts->isEmpty()) {
            return $this->sendError('No contacts found.');
        }

        return $this->sendResponse($contacts->toArray(), 'Contacts retrieved successfully.');
    }

    public function store(ContactCreateRequest $request, ContactRepository $repo)
    {
        $input = $request->all();

        $data = $request->validate($input);

        $contact = $repo->create($data);

        return $this->sendResponse($contact->toArray(), 'Contact created successfully.');
    }

    public function update(ContactUpdateRequest $request, ContactRepository $repo, Contact $contact)
    {
        $input = $request->all();

        $data = $request->validate($input);

        $contact = $repo->update($data, $contact);

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

        $data = $request->validate($input);

        $repo->updateImage($data, $contact);

        return $this->sendResponse($contact->toArray(), 'Profile photo updated.');
    }

}
