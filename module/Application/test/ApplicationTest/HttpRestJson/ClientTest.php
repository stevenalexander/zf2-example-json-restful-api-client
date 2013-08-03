<?php
namespace ApplicationTest\HttpRestJson;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_Assert;

use Application\HttpRestJson\Client as HttpRestJsonClient;
use Zend\Http\Response;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testGetReturnsAssociativeArrayContent()
    {
        $url = "http://api/test";
        $method = "GET";

        $responseContent = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_200);
        $response->setContent($responseContent);

        $mockHttpClient = $this->getMock('Zend\Http\Client', array('dispatch'));
        $mockHttpClient->expects($this->once())
                        ->method('dispatch')
                        ->will($this->returnCallback(function($request) use ($response, $method, $url) {
                            PHPUnit_Framework_Assert::assertSame($request->getMethod(), $method);
                            PHPUnit_Framework_Assert::assertSame($request->getUriString(), $url);
                            return $response;
                        }));

        $httpRestJsonClient = new HttpRestJsonClient($mockHttpClient);

        $this->assertSame(
            json_decode($responseContent, true),
            $httpRestJsonClient->get($url)
        );
    }
}