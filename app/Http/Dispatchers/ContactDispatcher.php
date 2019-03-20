<?php

namespace App\Http\Dispatchers;

use Illuminate\Http\Request;

class ContactDispatcher extends ApiRequestDispatcher
{
    public function dispatch($entity, $method, $extraParams = '', $formData = [])
    {
        $returnResponse = [];

        $routeName = $this->getRouteName($entity, $method);
        $actionMethod = $this->getMethod($method);

        $this->createRequest($routeName, $actionMethod, $extraParams, $formData);

        $this->setHeaders();

        $returnResponse['contact_response'] = $this->parseJsonResponse($this->sendRequest());

        if ($method == 'store') {
            if (array_key_exists('success', $returnResponse['contact_response']) && $returnResponse['contact_response']['success']) {

                if (!is_null($formData['phone_numbers'][0]['name'])) {
                    $phoneNumbers = $this->parsePhoneNumbers($formData, $returnResponse['contact_response']);

                    $returnResponse['phone_numbers_response'] = $this->dispatchPhoneNumbers($phoneNumbers);
                }
            }
        }
        return $returnResponse;
    }

    public function createRequest($routeName, $actionMethod, $extraParams, $formData)
    {
        $this->request = Request::create(route($routeName, $extraParams), $actionMethod);

        $this->request->request->add($formData);

    }

    public function dispatchPhoneNumbers($phoneNumbers)
    {
        $responsePhoneNumbers = [];

        $phoneNumberDispatcher = new PhoneNumberDispatcher();

        foreach ($phoneNumbers['phone_numbers'] as $phoneNumber) {
            $responsePhoneNumbers = $phoneNumberDispatcher->dispatch('phone-numbers', 'store', $phoneNumber);
        }

        return $responsePhoneNumbers;
    }
}
