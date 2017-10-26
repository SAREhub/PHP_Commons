<?php


namespace SAREhub\Commons\Http;


use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class HttpClientMockBuilderTest extends TestCase
{

    public function testAddResponse()
    {
        $response = new Response(200, [], "TEST");
        $builder = (new HttpClientMockBuilder())->response($response);
        $this->assertContains($response, $builder->getResponseQueue());
    }

    public function testAddRequestException()
    {
        $requestException = new RequestException("test", new Request("GET", "http://example.com/"));
        $builder = (new HttpClientMockBuilder())->requestException($requestException);
        $this->assertContains($requestException, $builder->getResponseQueue());
    }

    public function testSimpleResponse()
    {
        $realResponse = new Response(200, [], "TEST");
        $httpClient = (new HttpClientMockBuilder())->response($realResponse)->build();
        $response = $httpClient->get("/test");
        $this->assertEquals($realResponse, $response);
    }


}
