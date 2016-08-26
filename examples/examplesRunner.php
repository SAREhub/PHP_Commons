<?php

require dirname(__DIR__).'/vendor/autoload.php';
if (empty($argv[1])) {
	echo "USAGE: php examplesRunner.php <examplePath>";
	return;
}

$examplePath = __DIR__.'/'.$argv[1].'/example.php';

if (file_exists($examplePath)) {
	require $examplePath;
}




