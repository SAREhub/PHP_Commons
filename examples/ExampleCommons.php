<?php

require dirname(__DIR__).'/vendor/autoload.php';

function logMessage($message) {
	echo date('[H:i:s] ').$message."\n";
}

function runProcess($file, &$pipes) {
	return proc_open('php '.$file, [1 => ["pipe", "w"]], $pipes, __DIR__);
}

function printOutputFromProcess($label, $pipe) {
	logMessage("output from $label: ");
	echo "--------------------------------\n";
	echo stream_get_contents($pipe);
	echo "--------------------------------\n";
}