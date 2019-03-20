<?php

namespace App\Http\Dispatchers;

class ApiRequestDispatcher
{
    protected $request;

    public function getRouteName($entity, $actionMethod)
    {
        $route = '';
        if ($entity == 'phone_numbers')
            $route = 'contact.';

        return $route . $entity . '.' . $actionMethod;
    }

    public function getMethod($actionMethod)
    {
        $method = '';
        switch ($actionMethod) {
            case 'index':
                $method = 'GET';
                break;
            case 'store':
                $method = 'POST';
                break;
            case 'show':
                $method = 'GET';
                break;
            case 'update':
                $method = 'PUT';
                break;
            case 'destroy':
                $method = 'DELETE';
                break;
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
}
