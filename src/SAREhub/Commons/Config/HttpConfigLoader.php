<?php


namespace SAREhub\Commons\Config;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use JSend\InvalidJSendException;
use JSend\JSendResponse;
use SAREhub\Commons\Misc\Parameters;

class HttpConfigLoader implements ConfigLoader {
	
	/** @var string */
	private $url;
	
	/** @var Client */
	private $httpClient;
	
	public function __construct($url, Client $httpClient) {
		$this->url = $url;
		$this->httpClient = $httpClient;
	}
	
	public function load() {
		try {
			$response = $this->httpClient->get($this->url);
			return new Parameters(JSendResponse::decode($response->getBody())->getData());
		} catch (RequestException $e) {
			throw new ConfigLoaderException("Can't load config from url: ".$this->url, 0, $e);
		} catch (InvalidJSendException $e) {
			throw new ConfigLoaderException("Can't load config from url(invalid jsend response): ".$this->url, 0, $e);
		}
		
	}
}