<?php


namespace SAREhub\Commons\Logger;


use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\PsrLogMessageProcessor;
use SAREhub\Commons\Misc\InvokableProvider;

class StreamLoggerFactoryProvider extends InvokableProvider
{
    const DEFAULT_LOGGING_LEVEL = "DEBUG";
    const DEFAULT_STREAM = 'php://stdout';

    /**
     * @var string
     */
    private $level;

    /**
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * @var resource|string
     */
    private $stream;

    public function __construct(
        string $level = self::DEFAULT_LOGGING_LEVEL,
        ?FormatterInterface $formatter = null,
        $stream = self::DEFAULT_STREAM
    )
    {
        $this->level = $level;
        $this->formatter = $formatter ?? new DefaultJsonLogFormatter();
        $this->stream = $stream;
    }

    public function get(): LoggerFactory
    {
        return BasicLoggerFactory::newInstance()
            ->addProcessor(new PsrLogMessageProcessor())
            ->addHandler($this->createHandler());
    }

    private function createHandler(): HandlerInterface
    {
        $output = new StreamHandler($this->stream, $this->level);
        $output->setFormatter($this->formatter);
        return $output;
    }
}