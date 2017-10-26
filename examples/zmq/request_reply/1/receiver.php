<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\RequestReply\RequestReceiver;

require './ExampleCommons.php';

$receiver = RequestReceiver::inContext(new ZMQContext())
  ->bind(Dsn::tcp()->endpoint('127.0.0.1:30001'));
while (1) {
	logMessage("RECEIVER:");
	$request = $receiver->receiveRequest();
	logMessage("GOT REQUEST: ".$request);
	$receiver->sendReply("reply");
	logMessage("REPLY SENT");
	break;
}

$receiver->unbind();