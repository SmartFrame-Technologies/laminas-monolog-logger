<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;


use Interop\Container\ContainerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SocketHandler;
use Monolog\Logger;

class SocketHandlerFactory implements HandlerFactoryInterface
{
    use SpecialHandlersTrait;
    use FormatterTrait;
    use PropertiesTrait;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HandlerInterface
    {
        if (!isset($options['connectionString'])) {
            throw new MissingOptionsException('Missing "connectionString" option');
        }

        $handler = new SocketHandler($options['connectionString'], isset($options['level']) ? $options['level'] : Logger::DEBUG);

        if (isset($options['formatter'])) {
            $handler = $this->applyFormatters($handler, $options['formatter']);
        }

        if (isset($options['properties'])) {
            $this->applyProperties($handler, $options['properties']);
        }

        return $this->applySpecialHandlers($handler, $options);
    }
}
