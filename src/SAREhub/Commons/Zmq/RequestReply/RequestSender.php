<?php

namespace SAREhub\Commons\Zmq\RequestReply;

use SAREhub\Commons\Misc\Dsn;

/**
 * Sending request to ZMQ socket
 */
class RequestSender
{

    const WAIT = true;
    const DONT_WAIT = false;

    /**
     * @var Dsn
     */
    protected $dsn = null;

    /**
     * @var \ZMQContext
     */
    protected $context;

    /**
     * @var \ZMQSocket
     */
    protected $socket = null;

    public function __construct(\ZMQContext $context)
    {
        $this->context = $context;
    }

    public static function inContext(\ZMQContext $context)
    {
        return new self($context);
    }

    /**
     * @param Dsn $dsn
     * @return $this
     */
    public function connect(Dsn $dsn)
    {
        if ($this->isConnected()) {
            throw new \LogicException("Can't connect when socket is connected");
        }
        $this->dsn = $dsn;
        $this->getSocket()->connect((string)$dsn);
        return $this;
    }

    /**
     * @return $this
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->getSocket()->disconnect($this->dsn);
            $this->dsn = null;
        }

        return $this;
    }

    /**
     * Send request via ZMQ socket.
     * @param string $request Request payload.
     * @param bool $wait If true that operation would be block.
     * @return $this
     * @throws \ZMQSocketException
     */
    public function sendRequest($request, $wait = self::WAIT)
    {
        $this->getSocket()->send($request, ($wait) ? 0 : \ZMQ::MODE_DONTWAIT);
        return $this;
    }

    /**
     * Receive reply from ZMQ socket.
     * @param bool $wait If true that operation would be block.
     * @return bool|string
     * @throws \ZMQSocketException
     */
    public function receiveReply($wait = self::WAIT)
    {
        return $this->getSocket()->recv(($wait) ? 0 : \ZMQ::MODE_DONTWAIT);
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->getDsn() !== null;
    }

    /**
     * @return Dsn
     */
    public function getDsn()
    {
        return $this->dsn;
    }

    /**
     * @return \ZMQSocket
     */
    public function getSocket()
    {
        if ($this->socket === null) {
            $this->socket = $this->context->getSocket(\ZMQ::SOCKET_REQ, null, null);
        }

        return $this->socket;
    }

    /**
     * @return \ZMQContext
     */
    public function getContext()
    {
        return $this->context;
    }
}