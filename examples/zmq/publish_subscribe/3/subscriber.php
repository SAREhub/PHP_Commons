<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\PublishSubscribe\Subscriber;

require './ExampleCommons.php';

try {
    $subscriber = Subscriber::inContext(new ZMQContext())->connect(Dsn::tcp()->endpoint("127.0.0.1:30001"));
    $subscriber->subscribe("topic");
    logMessage("connected");
    logMessage("waiting for message...");

    for ($i = 1; $i < 20; ++$i) {
        logMessage("try receive $i");
        if ($message = $subscriber->receive()) {
            logMessage("Message: " . print_r($message, true));
            break;
        }
        sleep(1);
    }

    $subscriber->disconnectAll();
    logMessage("disconnected");
} catch (Exception $e) {
    echo $e;
}