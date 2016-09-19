<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\RequestReply\RequestReceiver;

require dirname(dirname(dirname(__DIR__))).'/vendor/autoload.php';

$receiver = RequestReceiver::inContext(new ZMQContext())
  ->bind(Dsn::tcp()->endpoint('127.0.0.1:30001'));
while (1) {
	echo "RECEIVER: \n";
	$request = $receiver->receiveRequest();
	echo "GOT REQUEST: ".$request."\n";
	$receiver->sendReply("reply");
	echo "REPLY SENT\n";
	break;
}

$receiver->unbind();