<?php

namespace App\Http\Controllers;

use App\Http\Dispatchers\ContactDispatcher;
use App\Http\Dispatchers\PhoneNumberDispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $contactRequestDispatcher;
    protected $phoneNumberRequestDispatcher;
    protected $entity;

    public function __construct()
    {
        $this->contactRequestDispatcher = new ContactDispatcher();

        $this->phoneNumberRequestDispatcher = new PhoneNumberDispatcher();
    }

}
