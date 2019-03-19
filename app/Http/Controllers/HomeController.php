<?php

namespace App\Http\Controllers;


use App\Contact;
use App\Http\ApiRequestDispatcher;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $responseData = $this->requestDispatcher->dispatch($this->entity, 'index');

        $contacts = [];

        if (empty($responseData['success'])) {
            return view('home')->with('contacts', $contacts);
        }

        $contacts = $responseData['data']['data'];

        return view('home')->with('contacts', $contacts);
    }

    public function contactCreateForm()
    {
        return view('form');

    }
    public function editContact()
    {

    }

    public function favouriteContact()
    {

    }
}
