<?php

declare(strict_types=1);

namespace SmartFrame\Logger\Factory;


use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerAbstractFactory implements AbstractFactoryInterface
{
    use LoggerFactoryTrait;

    protected const CONFIG_KEY = 'logger';

    public function canCreate(ContainerInterface $container, $requestedName): bool
    {
        $config = $this->getConfig($container);

        if (empty($config)) {
            return false;
        }

        return isset($config[$requestedName]);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): LoggerInterface
    {
        $loggerConfig = $this->getConfig($container);
        $serviceConfig = $loggerConfig[$requestedName];

        $handlers = $this->processHandlers($container,
            isset($serviceConfig['handlers']) ? $serviceConfig : $loggerConfig
        );
        $processors = $this->processProcessors($container,
            isset($serviceConfig['processors']) ? $serviceConfig : $loggerConfig
        );

        return new Logger($serviceConfig['channel_name'], $handlers, $processors);
    }

    protected function getConfig(ContainerInterface $container): array
    {
        return $container->get('config')[self::CONFIG_KEY] ?? [];
    }
}