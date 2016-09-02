<?php

use SAREhub\Commons\Process\PcntlSignals;

echo "PcntlSignals example\n\n";
echo "press CTRL+C to call SIGINT signal handler \n";

PcntlSignals::get()->handle(\SIGINT, function () {
	echo "\nkilled via signal\n";
	exit(0);
})->install();

while (true) {
	PcntlSignals::get()->checkPendingSignals();
};

