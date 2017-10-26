<?php

namespace SAREhub\Commons\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class HttpClientMockBuilder
{

    /** @var array */
    private $responseQueue = [];

    /**
     * @param Response $response
     * @return $this
     */
    public function response(Response $response)
    {
        $this->responseQueue[] = $response;
        return $this;
    }

    /**
     * @param RequestException $exception
     * @return $this
     */
    public function requestException(RequestException $exception)
    {
        $this->responseQueue[] = $exception;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponseQueue()
    {
        return $this->responseQueue;
    }

    /**
     * @return Client
     */
    public function build()
    {
        return new Client([
            'handler' => HandlerStack::create(new MockHandler($this->responseQueue))
        ]);
    }
}