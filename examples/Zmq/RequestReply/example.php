<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\RequestReply\RequestSender;

echo "Zmq.RequestReply example\n\n";

$p = proc_open('php '.__DIR__.'/receiver.php', [
  1 => array("pipe", "w")
], $pipes);

$sender = RequestSender::inContext(new ZMQContext())
  ->connect(Dsn::tcp()->endpoint('127.0.0.1:30001'));

$request = "request";
echo "SENDING REQUEST: ".$request;
$reply = $sender->sendRequest($request)->receiveReply();
echo "\nGOT REPLY: ".$reply."\n";

echo "\nOUTPUT FROM REQUEST RECEIVER: \n";
echo "\n--------------------------------\n";
echo stream_get_contents($pipes[1]);
echo "\n--------------------------------\n";

$sender->disconnect();
