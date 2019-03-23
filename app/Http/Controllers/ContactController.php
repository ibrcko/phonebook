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
        $failedContact = false;
        $messageContact = '';
        $errorsContact = '';

        $failedPhoneNumber = false;
        $messagePhoneNumber = '';
        $errorsPhoneNumber = '';

        $form = $request->all();

        $response = $this->contactRequestDispatcher->dispatch($this->entity, 'store', '', $form);

        if (array_key_exists('errors', $response['contact_response']) ||
            (array_key_exists('success', $response['contact_response']) &&
                !$response['contact_response']['success'])) {
            $failedContact = true;
            $messageContact = $response['contact_response']['message'];
            $errorsContact = $response['contact_response']['errors'];
        } else {
            $responseContact = $response['contact_response']['data']['id'];

            $contact = Contact::find($responseContact);
            $responseShow = $this->showContact($contact);
        }

        if ($failedContact) {
            return view('forms.contact')->with(['failed' => $failedContact, 'message' => $messageContact, 'errors' => $errorsContact]);
        }

        if (isset($response['phone_numbers_response'])) {
            if (array_key_exists('errors', $response['phone_numbers_response']) ||
                (array_key_exists('success', $response['phone_numbers_response']) &&
                    !$response['phone_numbers_response']['success'])) {
                $failedPhoneNumber = true;
                $messagePhoneNumber = $response['phone_numbers_response']['message'];
                $errorsPhoneNumber = $response['phone_numbers_response']['errors'];
            }
        }

        if ($failedPhoneNumber) {
            return $responseShow->with(['failedPn' => $failedPhoneNumber, 'message' => $messagePhoneNumber, 'errors' => $errorsPhoneNumber, 'created' => true]);
        }

        return $responseShow->with('created', true);

    }

    public function showContact(Contact $contact)
    {
        $responseData = $this->contactRequestDispatcher->dispatch($this->entity, 'show', $contact);

        $responseContact = $this->prepareContactData($responseData);

        return view('show')->with('contact', $responseContact)->with('failed', false);
    }

    private function prepareContactData($responseData)
    {
        if (array_key_exists('errors', $responseData['contact_response']) ||
            (array_key_exists('success', $responseData['contact_response']) &&
                !$responseData['contact_response']['success'])) {
            return $responseContact = [];
        } else {
            $responseContact = $responseData['contact_response']['data'];
        }

        return $responseContact;
    }

    public function deleteContact(Request $request, Contact $contact)
    {
        $message = 'Contact deleted successfully!';
        $referer = $request->header('referer');

        $response = $this->contactRequestDispatcher->dispatch($this->entity, 'destroy', $contact);

        if (!$response['contact_response']['success']) {
            $message = 'Contact wasn\'t deleted due to an error';
            return redirect($referer)->with('message', $message)->with('deletion', true);
        }

        return redirect('home')->with(['deletion' => true, 'message' => $message]);
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

        $referer = $request->header('referer');

        if (array_key_exists('errors', $responseData['contact_response'])) {
            return view('forms.contact-edit', $contact)->with('error', $responseData['contact_response']['errors'])->with('contact', $contact);
        } else if (strpos($referer, 'home') || strpos($referer, 'favourite') || strpos($referer, 'search')) {
            return redirect($referer);
        }

        return view('forms.contact-edit', $contact)->with('success', $responseData['contact_response']['success'])->with('contact', $contact);
    }

    public function search(Request $request)
    {

        $input = $request->all();

        $searchResponse = $this->contactRequestDispatcher->dispatch($this->entity, 'search', $input);

        $contacts = $this->prepareContactData($searchResponse);

        if (!empty($contacts)) {
            return view('home')->with('contacts', $contacts['data']);
        }
        return view('home')->with('contacts', $contacts);
    }

    public function searchFavourite(Request $request)
    {

        $input = $request->all();

        $searchResponse = $this->contactRequestDispatcher->dispatch($this->entity, 'favourite.search', $input);

        $contacts = $this->prepareContactData($searchResponse);

        if (!empty($contacts)) {
            return view('home-favourite')->with('contacts', $contacts['data']);
        }
        return view('home-favourite')->with('contacts', $contacts);
    }
}
