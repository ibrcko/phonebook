<?php

namespace App\Http\Dispatchers;

/**
 * Class PhoneNumberDispatcher
 * @package App\Http\Dispatchers
 */
class PhoneNumberDispatcher extends ApiRequestDispatcher
{

    /**
     * @param $entity
     * @param $method
     * @param string $extraParams
     * @param array $formData
     * @return mixed
     */
    public function dispatch($entity, $method, $extraParams = '', $formData = [])
    {
        $routeName = $this->getRouteName($entity, $method);
        $actionMethod = $this->getMethod($method);

        $this->createRequest($routeName, $actionMethod, $extraParams, $formData);

        $this->setHeaders();

        $response = $this->parseJsonResponse($this->sendRequest());

        return $response;
    }
}
