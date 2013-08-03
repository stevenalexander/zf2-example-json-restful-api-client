<?php

namespace Application\HttpRestJson;

use Zend\Http\Client as HttpClient;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;

class Client
{
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function get($url)
    {
		return $this->dispatchRequestAndDecodeResponse($url, "GET");
    }

    public function post($url, $data)
    {
        return $this->dispatchRequestAndDecodeResponse($url, "POST", $data);
    }

    public function put($url, $data)
    {
        return $this->dispatchRequestAndDecodeResponse($url, "PUT", $data);
    }

    public function delete($url)
    {
        return $this->dispatchRequestAndDecodeResponse($url, "DELETE");
    }

    protected function dispatchRequestAndDecodeResponse($url, $method, $data = null)
    {
        $request = new Request();
        $request->setUri($url);
        $request->setMethod($method);

        if ($data){
            $request->setPost(new Parameters($data));
        }

        $response = $this->httpClient->dispatch($request);
        # should interogate response status, throwing appropiate exceptions for error codes
        return json_decode($response->getBody(), true);
    }
}
