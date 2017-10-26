<?php

use SAREhub\Commons\Misc\Dsn;
use SAREhub\Commons\Zmq\PublishSubscribe\Subscriber;

echo "zmq.publish_subscribe example 2 \n";

try {
    $subscriber = Subscriber::inContext(new ZMQContext())
        ->bind(Dsn::tcp()->endpoint("127.0.0.1:30000"))
        ->subscribe("");
    logMessage("created and binded");
    logMessage("starting publisher");
    $p = runProcess(__DIR__ . '/publisher.php', $pipes);
    logMessage("publisher started");
    sleep(2);
    logMessage("waiting for message...");
    for ($i = 1; $i < 5; ++$i) {
        logMessage("try receive $i");
        if ($message = $subscriber->receive()) {
            logMessage("Message: " . print_r($message, true));
            break;
        }
        sleep(1);
    }

    $subscriber->unbind();
    logMessage("unbined");

    printOutputFromProcess("publisher", $pipes[1]);
} catch (Exception $e) {
    echo $e;
}