<?php
namespace App\Http\Dispatchers;

use Illuminate\Http\Request;
class ApiRequestDispatcher
{
    protected $request;

    public function getRouteName($entity, $actionMethod)
    {
        $route = '';

        return $route . $entity . '.' . $actionMethod;
    }

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

    public function setHeaders()
    {
        $this->request->headers->set(config('auth.apiAccess.apiKey'), config('auth.apiAccess.apiSecret'));

        $this->request->headers->set('accept', 'application/json');
    }

    public function parseJsonResponse($response)
    {
        $response = json_decode($response->content(), true);

        return $response;
    }

    public function sendRequest()
    {
        return $response = app()->handle($this->request);
    }

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

    public function createRequest($routeName, $actionMethod, $extraParams, $formData)
    {
        $this->request = Request::create(route($routeName, $extraParams), $actionMethod);

        $this->request->request->add($formData);

    }
}
