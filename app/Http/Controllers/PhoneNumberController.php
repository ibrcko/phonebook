<?php

namespace App\Http\Controllers;

use App\Contact;
use App\PhoneNumber;
use Illuminate\Http\Request;

/**
 * Class PhoneNumberController
 * @package App\Http\Controllers
 */
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

    /**
     * @param Contact $contact
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createPhoneNumber(Contact $contact)
    {
        return view('forms.phone-number')->with('contact', $contact);
    }

    /**
     * @param Contact $contact
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storePhoneNumber(Contact $contact, Request $request)
    {
        $failed = false;
        $message = '';
        $errors = [];

        $referer = $request->header('referer');

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
            return redirect($referer)->with(['failed' => $failed, 'message' => $message, 'errors' => $errors]);
        }

        return redirect($referer)->with(['failed' => $failed, 'message' => $message, 'errors' => $errors, 'createdPN' => true]);
    }

    /**
     * @param Request $request
     * @param $phoneNumber
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deletePhoneNumber(Request $request, $phoneNumber)
    {
        $referer = $request->header('referer');

        $this->phoneNumberRequestDispatcher->dispatch($this->entity, 'destroy', $phoneNumber, []);

        return redirect($referer)->with(['deletion' => true]);
    }
}
