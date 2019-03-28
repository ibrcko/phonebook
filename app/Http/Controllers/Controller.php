<?php

namespace App\Http\Controllers;

use App\Http\Dispatchers\ContactDispatcher;
use App\Http\Dispatchers\PhoneNumberDispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var ContactDispatcher
     */
    protected $contactRequestDispatcher;
    /**
     * @var PhoneNumberDispatcher
     */
    protected $phoneNumberRequestDispatcher;
    /**
     * @var
     */
    protected $entity;

    /**
     * Controller constructor.
     * Initializing dispatchers that will dispatch internal requests
     */
    public function __construct()
    {
        $this->contactRequestDispatcher = new ContactDispatcher();

        $this->phoneNumberRequestDispatcher = new PhoneNumberDispatcher();
    }

}
