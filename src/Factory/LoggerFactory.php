<?php

declare(strict_types=1);

namespace SmartFrame\Logger\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

final class LoggerFactory implements FactoryInterface
{
    use LoggerFactoryTrait;

    protected const CONFIG_KEY = 'logger';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): Logger
    {
        $config = $container->get('config')[self::CONFIG_KEY];

        return new Logger($config['channel_name'],
            $this->processHandlers($container, $config),
            $this->processProcessors($container, $config)
        );
    }

}
