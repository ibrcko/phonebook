<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->entity = 'contacts';

        $this->middleware('auth');
    }

    public function createContact()
    {
        return view('forms.contact');
    }

    public function storeContact(Request $request)
    {
        $form = $request->all();

        $response = $this->contactRequestDispatcher->dispatch($this->entity, 'store', '', $form);

        return $response;
    }

    public function deleteContact(Contact $contact)
    {
        $this->contactRequestDispatcher->dispatch($this->entity, 'destroy', $contact);

        return redirect(route('home'));
    }

    public function showContact(Contact $contact)
    {
        $responseData = $this->contactRequestDispatcher->dispatch($this->entity, 'show', $contact);

        $responseContact = $this->prepareContactData($responseData);

        return view('show')->with('contact', $responseContact);
    }

    public function editContact(Contact $contact)
    {
        $responseData = $this->contactRequestDispatcher->dispatch($this->entity, 'show', $contact);

        $responseContact = $this->prepareContactData($responseData);

        return view('forms.contact-edit')->with('contact', $responseContact);
    }

    public function updateContact(Contact $contact, Request $request)
    {
        $form = $request->all();

        $responseData = $this->contactRequestDispatcher->dispatch($this->entity, 'update', $contact->id, $form);

        if (array_key_exists('errors', $responseData['contact_response'])) {
            return view('forms.contact-edit', $contact)->with('error', $responseData['contact_response']['errors'])->with('contact', $contact);
        } else if ($request->header('referer') == route('home')) {
            return redirect(route('home'));
        }

        return view('forms.contact-edit', $contact)->with('success', $responseData['contact_response']['success'])->with('contact', $contact);
    }

    private function prepareContactData($responseData)
    {
        if (!array_key_exists('success', $responseData['contact_response'])) {
            return $responseContact = [];
        }

        $responseContact = $responseData['contact_response']['data'];

        return $responseContact;
    }
}
