<?php

declare(strict_types=1);

namespace SmartFrame\Logger\Initializer;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Initializer\InitializerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class LoggerAwareInitializer implements InitializerInterface
{
    public function __invoke(ContainerInterface $container, $instance)
    {
        if (!$instance instanceof LoggerAwareInterface) {
            return;
        }
        $instance->setLogger($container->get(LoggerInterface::class));
    }

}