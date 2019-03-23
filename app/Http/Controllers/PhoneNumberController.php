<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class PhoneNumberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->entity = 'phone-numbers';

        $this->middleware('auth');
    }

    public function createPhoneNumber(Contact $contact)
    {
        return view('forms.phone-number')->with('contact', $contact);
    }

    public function storePhoneNumber(Contact $contact, Request $request)
    {
        $failed = false;
        $message = '';
        $errors = [];

        $form = $request->all();

        $responsePhoneNumber = $this->phoneNumberRequestDispatcher->parsePhoneNumbers($form, $contact);

        $response = $this->contactRequestDispatcher->dispatchPhoneNumbers($responsePhoneNumber);

        if (array_key_exists('errors', $response) ||
            (array_key_exists('success', $response) &&
                !$response['success'])) {
            $failed = true;
            $message = $response['message'];
            $errors = $response['errors'];
        }
        if ($failed) {
            return view('forms.phone-number')->with(['contact' => $contact,     'failed' => $failed, 'message' => $message, 'errors' => $errors]);
        }
        $contactArray = $contact->toArray();
        $contactArray['phone_numbers'][] = $response['data'];

        return view('show')->with('contact', $contactArray)->with(['failed' => $failed, 'message' => $message, 'errors' => $errors, 'createdPN' => true]);
    }

    public function deletePhoneNumber(Contact $contact)
    {
        $this->phoneNumberRequestDispatcher->dispatch($this->entity, 'destroy', [], $contact);

        return redirect(route('home'));
    }
}
