<?php

namespace App\Http\Controllers;


use App\Http\ApiRequestDispatcher;

class HomeController extends Controller
{
    protected $requestDispatcher;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->requestDispatcher = new ApiRequestDispatcher();

        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $response = $this->requestDispatcher->dispatch('index');
        dd($response);

        return view('home');
    }
}
