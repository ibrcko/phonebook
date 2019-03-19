<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\ApiRequestDispatcher;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected $requestDispatcher;

    protected $entity;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->requestDispatcher = new ApiRequestDispatcher();
        $this->entity = 'contacts';

        $this->middleware('auth');
    }

    public function createContact(Request $request)
    {
        $form = $request->all();

        $response = $this->requestDispatcher->dispatch($this->entity, 'store', '', $form);

        return $response;
    }

    public function deleteContact(Contact $contact)
    {
        $this->requestDispatcher->dispatch($this->entity, 'destroy', $contact);

        return redirect(route('home'));
    }

}
