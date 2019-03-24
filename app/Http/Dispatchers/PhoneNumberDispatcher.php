<?php
namespace App\Http\Dispatchers;

class PhoneNumberDispatcher extends ApiRequestDispatcher
{

    public function dispatch($entity, $method,  $extraParams = '', $formData = [])
    {
        $routeName = $this->getRouteName($entity, $method);
        $actionMethod = $this->getMethod($method);

        $this->createRequest($routeName, $actionMethod, $extraParams, $formData);

        $this->setHeaders();

        $response = $this->parseJsonResponse($this->sendRequest());

        return $response;
    }
}
