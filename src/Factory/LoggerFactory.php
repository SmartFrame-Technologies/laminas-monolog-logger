<?php

declare(strict_types=1);

namespace SmartFrame\Logger\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

final class LoggerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Logger
    {
        $config = $container->get('config')['logger'];
        $handlers = [];
        $processors = [];

        if (isset($config['handlers'])) {
            foreach ($config['handlers'] as $handler => $configuration) {
                if ($container->has($handler)) {
                    $handlers[] = $container->get($handler);
                } else {
                    $handlers[] = new $handler(...array_values($configuration));
                }
            }
        }

        if (isset($config['processors'])) {
            foreach ($config['processors'] as $processor => $configuration) {
                if ($container->has($processor)) {
                    $processors[] = $container->get($processor);
                } else {
                    $processors[] = new $processor(...array_values($configuration));
                }
            }
        }

        return new Logger($config['channel_name'], $handlers, $processors);
    }

}
