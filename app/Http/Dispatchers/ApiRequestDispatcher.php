<?php

namespace App\Http\Dispatchers;

use Illuminate\Http\Request;

/**
 * Class ApiRequestDispatcher
 * @package App\Http\Dispatchers
 * Class for a dispatcher that prepares data for dispatching an internal request
 * It also dispatches the request
 */
class ApiRequestDispatcher
{
    /**
     * @var
     */
    protected $request;

    /**
     * @param $entity
     * @param $actionMethod
     * @return string
     * Method that concatenates entity name and action method to generate a route name
     */
    public function getRouteName($entity, $actionMethod)
    {
        $route = '';

        return $route . $entity . '.' . $actionMethod;
    }

    /**
     * @param $actionMethod
     * @return string
     * Method that depending on the action method returns HTTP method name
     */
    public function getMethod($actionMethod)
    {
        $method = 'GET';
        switch ($actionMethod) {
            case 'store':
                $method = 'POST';
                break;
            case 'update':
                $method = 'PUT';
                break;
            case 'destroy':
                $method = 'DELETE';
        }
        return $method;
    }

    /**
     * Method that adds headers to the HTTP request for authorization
     */
    public function setHeaders()
    {
        $this->request->headers->set(config('auth.apiAccess.apiKey'), config('auth.apiAccess.apiSecret'));

        $this->request->headers->set('accept', 'application/json');
    }

    /**
     * @param $routeName
     * @param $actionMethod
     * @param $extraParams
     * @param $formData
     * Method that creates request with given data
     */
    public function createRequest($routeName, $actionMethod, $extraParams, $formData)
    {
        $this->request = Request::create(route($routeName, $extraParams), $actionMethod);

        $this->request->request->add($formData);

    }

    /**
     * @return mixed
     * Method that sends the request
     */
    public function sendRequest()
    {
        return $response = app()->handle($this->request);
    }

    /**
     * @param $response
     * @return mixed
     * Method that decodes json response
     */
    public function parseJsonResponse($response)
    {
        $response = json_decode($response->content(), true);

        return $response;
    }

    /**
     * @param $formData
     * @param $contactOrResponse
     * @return array
     * Method that parses and forms data from phone numbers response
     */
    public function parsePhoneNumbers($formData, $contactOrResponse)
    {
        $phoneNumbersForm = $formData['phone_numbers'];

        $phoneNumbers = [];

        foreach ($phoneNumbersForm as $key => $number) {
            if (!is_null($number['name']) || !is_null($number['number']) || !is_null($number['label'])) {
                if (is_array($contactOrResponse)) {
                    $number['contact_id'] = $contactOrResponse['data']['id'];
                } else {
                    $number['contact_id'] = $contactOrResponse->id;
                }

                $phoneNumbers['phone_numbers'][] = $number;
            }
        }
        return $phoneNumbers;
    }
}
