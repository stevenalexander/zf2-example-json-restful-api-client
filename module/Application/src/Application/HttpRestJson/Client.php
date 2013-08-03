<?php

namespace Application\HttpRestJson;

use Zend\Http\Client as HttpClient;
use Zend\Http\Request;

class Client
{
    public function get()
    {
		$request = new Request();
        $request->setUri('https://api.github.com/users/defunkt');
        $httpClient = new HttpClient();
        $httpClient->setAdapter('Zend\Http\Client\Adapter\Curl');

        $response = $httpClient->dispatch($request);

		return $response->getBody();
    }
}
