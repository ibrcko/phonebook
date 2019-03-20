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
        $form = $request->all();

        $phoneNumbers = $this->phoneNumberRequestDispatcher->parsePhoneNumbers($form, $contact);
        $response = $this->contactRequestDispatcher->dispatchPhoneNumbers($phoneNumbers);

        return $response;
    }

    public function deletePhoneNumber(Contact $contact)
    {
        $this->phoneNumberRequestDispatcher->dispatch($this->entity, 'destroy', [], $contact);

        return redirect(route('home'));
    }
}
