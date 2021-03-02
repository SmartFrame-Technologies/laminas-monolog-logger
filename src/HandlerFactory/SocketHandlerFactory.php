<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;


use Interop\Container\ContainerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SocketHandler;

class SocketHandlerFactory implements HandlerFactoryInterface
{
    use SpecialHandlersTrait;
    use FormatterTrait;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HandlerInterface
    {
        if (!isset($options['connectionString'])) {
            throw new MissingOptionsException('Missing "connectionString" option');
        }

        $handler = new SocketHandler($options['connectionString']);

        if (isset($options['formatter'])) {
            $handler = $this->applyFormatters($handler, $options['formatter']);
        }

        return $this->applySpecialHandlers($handler, $options);
    }
}
