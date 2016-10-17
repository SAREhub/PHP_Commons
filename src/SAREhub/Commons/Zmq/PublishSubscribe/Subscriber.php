<?php

namespace SAREhub\Commons\Zmq\PublishSubscribe;

use SAREhub\Commons\Zmq\ZmqSocketSupport;

/**
 * Represents subscriber ZMQ socket
 */
class Subscriber extends ZmqSocketSupport {
	
	/**
	 * @var array
	 */
	private $topics = [];

    public function __construct(\ZMQSocket $socket) {
        parent::__construct($socket);
	}

	/**
	 * @param \ZMQContext $context
	 * @return Subscriber
	 */
	public static function inContext(\ZMQContext $context) {
        return new self($context->getSocket(\ZMQ::SOCKET_SUB, null, null));
	}
	
	/**
	 * @param bool $wait
	 * @return null|array
	 */
	public function receive($wait = false) {
        if ($this->isBindedOrConnected()) {
			$mode = ($wait) ? 0 : \ZMQ::MODE_DONTWAIT;
			$parts = $this->getSocket()->recvMulti($mode);
			
			if ($parts) {
				return ['topic' => $parts[0], 'body' => $parts[1]];
			}
			
			return null;
		}

        throw new \LogicException("Can't receive message when socket isn't connected or binded");
	}

    /**
	 * @param $topic
	 * @return $this
	 */
	public function subscribe($topic) {
		if (!$this->isSubscribed($topic)) {
			$this->getSocket()->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, $topic);
			$this->topics[$topic] = true;
		}
		
		return $this;
	}
	
	public function unsubscribe($topic) {
		if ($this->isSubscribed($topic)) {
			$this->getSocket()->setSockOpt(\ZMQ::SOCKOPT_UNSUBSCRIBE, $topic);
			unset($this->topics[$topic]);
		}
		
		return $this;
	}
	
	/**
	 * @param string $topic
	 * @return bool
	 */
	public function isSubscribed($topic) {
		return isset($this->topics[$topic]);
	}
	
	/**
	 * @return array
	 */
	public function getTopics() {
		return array_keys($this->topics);
	}
}