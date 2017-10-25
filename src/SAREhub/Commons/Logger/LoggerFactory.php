<?php

namespace SAREhub\Commons\Logger;

use Psr\Log\LoggerInterface;

interface LoggerFactory {
	
	/**
	 * @param string $name
	 * @return LoggerInterface
	 */
	public function create($name);
}