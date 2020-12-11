<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;


use Interop\Container\ContainerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;

class StreamHandlerFactory extends AbstractHandlerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HandlerInterface
    {
        $handlerOptions = $container->get('config')['logger']['handlers'][StreamHandler::class];

        $handler = new StreamHandler($handlerOptions['stream']);

        return $this->applySpecialHandlers($handler, $handlerOptions);
    }
}