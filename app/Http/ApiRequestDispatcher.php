<?php

namespace App\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ApiRequestDispatcher
{
    protected $request;

    public function getRouteName($entity, $actionMethod)
    {
        return $entity . '.' . $actionMethod;

    }

    public function createRequest($routeName, $actionMethod, $extraParams, $formData)
    {
        $this->request = Request::create(route($routeName, $extraParams), $actionMethod);

        $this->request->request->add($formData);

    }

    public function dispatch($entity, $method, $extraParams = '', $formData = [])
    {
        $routeName = $this->getRouteName($entity, $method);
        $actionMethod = $this->getMethod($method);

        $this->createRequest($routeName, $actionMethod, $extraParams, $formData);

        $this->setHeaders();
        
        $response = $this->parseJsonResponse($this->sendRequest());

        return $response;
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

    private function parseJsonResponse($response)
    {
        $response = json_decode($response->content(), true);

        return $response;
    }

    private function sendRequest()
    {
        return $response = app()->handle($this->request);
    }
}
