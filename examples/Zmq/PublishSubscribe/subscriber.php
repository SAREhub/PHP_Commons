<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\PublishSubscribe\Subscriber;

require dirname(dirname(dirname(__DIR__))).'/vendor/autoload.php';

function logMessage($message) {
	echo date('[H:i:s] ').$message."\n";
}

try {
	$subscriber = Subscriber::inContext(new ZMQContext())->connect(Dsn::tcp()->endpoint("127.0.0.1:10000"));
	$subscriber->subscribe("topic");
	logMessage("Connected");
	logMessage("Waiting for message");
	
	for ($i = 1; $i < 20; ++$i) {
		logMessage("try receive $i");
		if ($message = $subscriber->receive()) {
			logMessage("Message: ".print_r($message, true));
			break;
		}
		sleep(1);
	}

	$subscriber->disconnectAll();
	logMessage("Disconnected");
} catch (Exception $e) {
	echo $e;
}