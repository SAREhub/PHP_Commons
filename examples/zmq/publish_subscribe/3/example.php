<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\PublishSubscribe\Publisher;
use SAREhub\Commons\Zmq\PublishSubscribe\Subscriber;
use SAREhub\Commons\Zmq\PublishSubscribe\ZmqForwarderDevice;

echo "zmq.publish_subscribe example 3 \n";

$localhost = '127.0.0.1';

try {
	logMessage("initialize forwarder device");
	$zmqContext = new ZMQContext();
	$device = ZmqForwarderDevice::getBuilder()
	  ->frontend(Subscriber::inContext($zmqContext)
		->bind(Dsn::tcp()->endpoint($localhost.':30000'))
	  )->backend(Publisher::inContext($zmqContext)
		->bind(Dsn::tcp()->endpoint($localhost.':30001'))
	  )->build();
	
	// stop device after some time
	$start = time();
	$data = [];
	$device->setTimerCallback(function () use ($start, &$data) {
		if (empty($data)) {
			logMessage("starting publisher");
			$data['pubProcess'] = runProcess(__DIR__.'/publisher.php', $pipesPublisher);
			$data['pubPipes'] = $pipesPublisher;
			logMessage("publisher started");
			
			logMessage("starting subscriber");
			$data['subProcess'] = runProcess(__DIR__.'/subscriber.php', $pipesSubscriber);
			$data['subPipes'] = $pipesSubscriber;
			logMessage("subscriber started");
		}
		return time() < ($start + 5);
		
	});
	
	logMessage('device run');
	$device->run();
	logMessage('device stopped');
	
	printOutputFromProcess("publisher", $data['pubPipes'][1]);
	printOutputFromProcess("subscriber", $data['subPipes'][1]);
} catch (Exception $e) {
	echo $e;
}