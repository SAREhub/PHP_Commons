<?php

namespace SAREhub\Commons\Test;

use Mockery\MockInterface;

class CallableMock
{
    public function __invoke()
    {

    }

    /**
     * @return MockInterface | callable
     */
    public static function create(): callable
    {
        return \Mockery::mock(self::class);
    }
}