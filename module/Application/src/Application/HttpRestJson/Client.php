<?php

namespace Application\HttpRestJson;

use Zend\Http\Client as HttpClient;
use Zend\Http\Request;

class Client
{
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function get($url)
    {
		$request = new Request();
        $request->setUri($url);

        $response = $this->httpClient->dispatch($request);
		# should interogate response status, throwing appropiate exceptions for error codes

		return json_decode($response->getBody(), true);
    }
}
