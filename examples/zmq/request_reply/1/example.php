<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\RequestReply\RequestSender;

echo "zmq.request_reply example\n\n";

$p = runProcess(__DIR__.'/receiver.php', $pipes);

$sender = RequestSender::inContext(new ZMQContext())
  ->connect(Dsn::tcp()->endpoint('127.0.0.1:30001'));

$request = "request";
logMessage("SENDING REQUEST: ".$request);
$reply = $sender->sendRequest($request)->receiveReply();
logMessage("GOT REPLY: ".$reply);

logMessage("OUTPUT FROM REQUEST RECEIVER: ");
echo "--------------------------------\n";
echo stream_get_contents($pipes[1]);
echo "--------------------------------\n";

$sender->disconnect();
