<?php

namespace App\Http\Controllers;


use http\Env\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

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
        $responseData = $this->contactRequestDispatcher->dispatch($this->entity, 'index');

        $contacts = [];

        if (empty($responseData['contact_response']['success'])) {
            return view('home')->with('contacts', $contacts);
        }

        $contacts = $responseData['contact_response']['data']['data'];

        return view('home')->with('contacts', $contacts);
    }

    public function favourite()
    {
        $responseData = $this->contactRequestDispatcher->dispatch($this->entity, 'favourite');

        $contacts = [];

        if (empty($responseData['contact_response']['success'])) {
            return view('home-favourite')->with('contacts', $contacts);
        }

        $contacts = $responseData['contact_response']['data']['data'];

        return view('home-favourite')->with('contacts', $contacts);
    }

    public function editContact()
    {

    }

    public function favouriteContact()
    {

    }
}
