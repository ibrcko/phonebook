<?php

namespace App\Http\Controllers;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function favourite()
    {
        $responseData = $this->contactRequestDispatcher->dispatch($this->entity, 'favourite.index');

        $contacts = [];

        if (empty($responseData['contact_response']['success'])) {
            return view('home-favourite')->with('contacts', $contacts);
        }

        $contacts = $responseData['contact_response']['data']['data'];

        return view('home-favourite')->with('contacts', $contacts);
    }
}
