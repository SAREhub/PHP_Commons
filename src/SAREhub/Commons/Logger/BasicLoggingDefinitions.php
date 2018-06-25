<?php


namespace SAREhub\Commons\Logger;


use function DI\autowire;
use function DI\create;
use DI\Definition\Helper\CreateDefinitionHelper;
use DI\Definition\Helper\DefinitionHelper;
use function DI\factory;

class BasicLoggingDefinitions
{
    public static function get(): array
    {
        return [
            StreamLoggerFactoryProvider::class => autowire()
                ->constructorParameter("level", factory(EnvLoggingLevelProvider::class))
                ->constructorParameter("formatter", create(DefaultJsonLogFormatter::class)),
            LoggerFactory::class => factory(StreamLoggerFactoryProvider::class)
        ];
    }

    public static function inject(CreateDefinitionHelper $definition, string $loggerName): DefinitionHelper
    {
        return $definition->method("setLogger", factory(LoggerFactory::class . "::create")
            ->parameter("name", $loggerName));
    }
}