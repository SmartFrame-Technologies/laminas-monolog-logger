<?php

declare(strict_types=1);

namespace SmartFrame\Logger\Factory;


use Psr\Container\ContainerInterface;

trait LoggerFactoryTrait
{
    protected function processHandlers(ContainerInterface $container, array $config): array
    {
        $handlers = [];

        if (isset($config['handlers'])) {
            foreach ($config['handlers'] as $handler => $configuration) {
                if ($container->has($handler)) {
                    $handlers[] = $container->build($handler, $configuration);
                } else {
                    $handlers[] = new $handler(...array_values($configuration));
                }
            }
        }

        return $handlers;
    }

    protected function processProcessors(ContainerInterface $container, array $config): array
    {
        $processors = [];

        if (isset($config['processors'])) {
            foreach ($config['processors'] as $processor => $configuration) {
                if ($container->has($processor)) {
                    $processors[] = $container->get($processor);
                } else {
                    $processors[] = new $processor(...array_values($configuration));
                }
            }
        }

        return $processors;
    }
}
