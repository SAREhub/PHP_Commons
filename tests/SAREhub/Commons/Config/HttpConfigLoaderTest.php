<?php


namespace SAREhub\Commons\Config;


use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JSend\JSendResponse;
use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Http\HttpClientMockBuilder;

class HttpConfigLoaderTest extends TestCase {
	
	public function testLoadSimpleConfig() {
		$httpClient = (new HttpClientMockBuilder())->response(
		  new Response(200, [], JSendResponse::success(["field" => "value"])->encode())
		)->build();
		
		$loader = new HttpConfigLoader("test/test2", $httpClient);
		$config = $loader->load();
		$this->assertEquals("value", $config->getRequired("field"));
	}
	
	public function testInvalidJSendResponse() {
		$this->expectException(ConfigLoaderException::class);
		
		$httpClient = (new HttpClientMockBuilder())->response(
		  new Response(200, [], json_encode(['data' => ["field" => "value"]]))
		)->build();
		
		$loader = new HttpConfigLoader("test/test2", $httpClient);
		$config = $loader->load();
	}
	
	public function testRequestError() {
		$this->expectException(ConfigLoaderException::class);
		$httpClient = (new HttpClientMockBuilder())->requestException(
		  new RequestException("message", new Request("GET", "http://example.com"))
		)->build();
		
		$loader = new HttpConfigLoader("test/test2", $httpClient);
		$config = $loader->load();
	}
}
