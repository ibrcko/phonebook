<?php
namespace App\Http;

use Illuminate\Http\Request;

class ApiRequestDispatcher
{
    protected $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function formUrl($type)
    {
        $url = '';
        switch ($type) {
            case 'index':
                $url = route('contacts.' . $type);

                break;
        }
        return $url;
    }
    public function getMethod($type)
    {
        $method = '';
        switch ($type) {
            case 'index':
                $method = 'GET';

                break;
        }
        return $method;
    }

    public function dispatch($type)
    {
        $url = $this->formUrl($type);
        $method = $this->getMethod($type);

        $this->request = Request::create($url, $method);
        $this->setHeaders();

        return $this->sendRequest();

    }

    public function setHeaders()
    {
        $this->request->headers->set(config('auth.apiAccess.apiKey'), config('auth.apiAccess.apiSecret'));

        $this->request->headers->set('accept', 'application/json');
    }

    private function sendRequest()
    {
        return $response = app()->handle($this->request);
    }
}
