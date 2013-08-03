<?php
namespace ApplicationTest\HttpRestJson;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_Assert;

use Application\HttpRestJson\Client as HttpRestJsonClient;
use Zend\Http\Response;
use Zend\Stdlib\Parameters;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testGetReturnsAssociativeArrayContent()
    {
        $url = "http://api/test";
        $method = "GET";
        $responseContent = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

        $mockHttpClient = $this->getMockForSuccessfulHttpClientDispatch($url, $method, $responseContent);

        $httpRestJsonClient = new HttpRestJsonClient($mockHttpClient);

        $this->assertSame(
            json_decode($responseContent, true),
            $httpRestJsonClient->get($url)
        );
    }

    public function testPostReturnsAssociativeArrayContent()
    {
        $url = "http://api/test";
        $method = "POST";
        $responseContent = '{"id":1}';
        $data = array("active" => true, "val" => "test");

        $mockHttpClient = $this->getMockForSuccessfulHttpClientDispatch(
            $url,
            $method,
            $responseContent,
            $data
        );

        $httpRestJsonClient = new HttpRestJsonClient($mockHttpClient);

        $this->assertSame(
            json_decode($responseContent, true),
            $httpRestJsonClient->post($url, $data)
        );
    }

    public function testPutReturnsAssociativeArrayContent()
    {
        $url = "http://api/test";
        $method = "PUT";
        $responseContent = '{"id":1,"active":true,"val":"test"}';
        $data = array("id" => 1, "active" => true, "val" => "test");

        $mockHttpClient = $this->getMockForSuccessfulHttpClientDispatch(
            $url,
            $method,
            $responseContent,
            $data
        );

        $httpRestJsonClient = new HttpRestJsonClient($mockHttpClient);

        $this->assertSame(
            json_decode($responseContent, true),
            $httpRestJsonClient->put($url, $data)
        );
    }

    public function testDeleteReturnsAssociativeArrayContent()
    {
        $url = "http://api/test/1";
        $method = "DELETE";
        $responseContent = '{"data":"id 1 has been deleted"}';

        $mockHttpClient = $this->getMockForSuccessfulHttpClientDispatch($url, $method, $responseContent);

        $httpRestJsonClient = new HttpRestJsonClient($mockHttpClient);

        $this->assertSame(
            json_decode($responseContent, true),
            $httpRestJsonClient->delete($url)
        );
    }

    protected function getMockForSuccessfulHttpClientDispatch($url, $method, $responseContent, $postData = null)
    {
        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_200);
        $response->setContent($responseContent);

        $mockHttpClient = $this->getMock('Zend\Http\Client', array('dispatch'));
        $mockHttpClient->expects($this->once())
                        ->method('dispatch')
                        ->will($this->returnCallback(function($request) use ($response, $method, $url, $postData) {
                            PHPUnit_Framework_Assert::assertSame($request->getMethod(), $method);
                            PHPUnit_Framework_Assert::assertSame($request->getUriString(), $url);

                            if ($postData) {
                                PHPUnit_Framework_Assert::assertSame($request->getPost()->getArrayCopy(), $postData);
                            }
                            return $response;
                        }));

        return $mockHttpClient;
    }
}