<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\PublishSubscribe\Publisher;

require './ExampleCommons.php';

try {
	$publisher = Publisher::inContext(new ZMQContext())->connect(Dsn::tcp()->endpoint("127.0.0.1:30000"));
	logMessage("created and connected");
	logMessage("publishing message ...");
	sleep(2);
	$publisher->publish("topic", "message", true);
	sleep(3);
	$publisher->disconnectAll();
	logMessage("disconnected");
} catch (Exception $e) {
	echo $e;
}