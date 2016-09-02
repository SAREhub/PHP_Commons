<?php

use SAREhub\Commons\Misc\DsnBuilder;
use SAREhub\Commons\Zmq\RequestReply\RequestSender;

echo "Zmq.RequestReply example\n\n";

$p = proc_open('php '.__DIR__.'/receiver.php', [
  1 => array("pipe", "w")
], $pipes);

$sender = RequestSender::builder()
  ->context(new ZMQContext())
  ->connect(DsnBuilder::tcp()->endpoint('127.0.0.1:30001'))->build();

$request = "request";
echo "SENDING REQUEST: ".$request;
$reply = $sender->sendRequest($request)->receiveReply();
echo "\nGOT REPLY: ".$reply."\n";

echo "\nOUTPUT FROM REQUEST RECEIVER: \n";
echo "\n--------------------------------\n";
echo stream_get_contents($pipes[1]);
echo "\n--------------------------------\n";

