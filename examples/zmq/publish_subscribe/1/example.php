<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\PublishSubscribe\Publisher;

echo "zmq.publish_subscribe example 1 \n";

$publisher = Publisher::inContext(new ZMQContext())
    ->bind(Dsn::tcp()->endpoint('127.0.0.1:10000'));
logMessage("created and binded");
logMessage("start subscriber");

$p = runProcess(__DIR__ . '/subscriber.php', $pipes);

logMessage("started subscriber");
sleep(2);

logMessage("publishing message ...");
$publisher->publish("topic", "message", true);
sleep(2);
$publisher->unbind();
logMessage("unbinded");
printOutputFromProcess("subscriber", $pipes[1]);

