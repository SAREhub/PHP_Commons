<?php

namespace SAREhub\Commons\DI\Test;

use DI\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use SAREhub\Commons\Logger\LoggerFactory;
use SAREhub\Commons\Logger\NullLoggerFactory;
use SAREhub\Commons\Secret\SecretValueProvider;
use SAREhub\Commons\Secret\SimpleSecretValueProvider;
use function DI\create;

abstract class ContainerAwareIntegrationTestCase extends TestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected function setUp()
    {
        $this->container = $this->buildContainer();
    }

    protected function buildContainer(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->useAnnotations(false);
        $builder->useAutowiring(true);
        $this->addDefinitions($builder);
        return $builder->build();
    }

    protected function addDefinitions(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            SecretValueProvider::class => create(SimpleSecretValueProvider::class),
            LoggerFactory::class => create(NullLoggerFactory::class)
        ]);
    }
}
