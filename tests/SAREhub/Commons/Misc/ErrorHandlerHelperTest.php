<?php

namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class ErrorHandlerHelperTest extends TestCase
{

    public function testConvertingErrorToException()
    {
        ErrorHandlerHelper::registerDefaultErrorHandler();
        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessage("Undefined variable: undefined");
        echo $undefined;
    }
}
