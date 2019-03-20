<?php

namespace App\Http\Dispatchers;


use Illuminate\Http\Request;

class PhoneNumberDispatcher extends ApiRequestDispatcher
{
    public function createRequest($routeName, $actionMethod, $extraParams, $formData)
    {
        $this->request = Request::create(route($routeName, $extraParams), $actionMethod);

        $this->request->request->add($formData);

    }

    public function dispatch($entity, $method, $formData = [], $extraParams = '')
    {
        $routeName = $this->getRouteName($entity, $method);
        $actionMethod = $this->getMethod($method);

        $this->createRequest($routeName, $actionMethod, $extraParams, $formData);

        $this->setHeaders();

        $response = $this->parseJsonResponse($this->sendRequest());

        return $response;
    }
}
