<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\PublishSubscribe\Publisher;
use SAREhub\Commons\Zmq\RequestReply\RequestSender;

echo "Zmq.PublishSubscribe example\n";

function logMessage($message) {
	echo date('[H:i:s] ').$message."\n";
}

$publisher = Publisher::inContext(new ZMQContext())->bind(Dsn::tcp()->endpoint('127.0.0.1:10000'));
logMessage("Connected");
logMessage("start subscriber");

$p = proc_open('php '.__DIR__.'/subscriber.php', [
  1 => array("pipe", "w")
], $pipes);

logMessage("started subscriber");
sleep(2);

logMessage("Publishing message ...");
$publisher->publish("topic", "message", true);
sleep(5);
$publisher->unbind();
logMessage("unbinded");

echo "\nOutput from subscriber: \n";
echo "\n--------------------------------\n";
echo stream_get_contents($pipes[1]);
echo "\n--------------------------------\n";

