<?php


namespace SAREhub\Commons\Misc;


use ErrorException;

class ErrorHandlerHelper
{
    public static function registerDefaultErrorHandler()
    {
        self::enableErrorReporting(E_ALL);
        self::hideDisplayErrors();
        self::registerErrorToExceptionHandler(E_ALL);
    }

    public static function enableErrorReporting($errorTypes)
    {
        ini_set('error_reporting', $errorTypes);
    }

    public static function hideDisplayErrors()
    {
        ini_set('display_errors', "Off");
    }

    public static function registerErrorToExceptionHandler($errorTypes = E_ALL)
    {
        $handler = function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        };
        set_error_handler($handler, $errorTypes);
    }
}