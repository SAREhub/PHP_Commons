<?php

use SAREhub\Commons\Misc\DsnBuilder;
use SAREhub\Commons\Zmq\RequestReply\RequestReceiver;

require dirname(dirname(dirname(__DIR__))).'/vendor/autoload.php';

$receiver = RequestReceiver::builder()
  ->context(new ZMQContext())
  ->bind(DsnBuilder::tcp()->endpoint('127.0.0.1:30001'))->build();
while (1) {
	echo "RECEIVER: \n";
	$request = $receiver->receiveRequest();
	echo "GOT REQUEST: ".$request."\n";
	$receiver->sendReply("reply");
	echo "REPLY SENT\n";
	break;
}