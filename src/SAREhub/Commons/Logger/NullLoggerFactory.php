<?php

namespace SAREhub\Commons\Logger;

use Psr\Log\NullLogger;

class NullLoggerFactory implements LoggerFactory
{

    public function create($name)
    {
        return new NullLogger();
    }
}