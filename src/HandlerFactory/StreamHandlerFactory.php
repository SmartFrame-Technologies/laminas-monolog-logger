<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;


use Interop\Container\ContainerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;

class StreamHandlerFactory implements HandlerFactoryInterface
{
    use SpecialHandlersTrait;
    use FormatterTrait;
    use PropertiesTrait;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HandlerInterface
    {
        if (!isset($options['stream'])) {
            throw new MissingOptionsException('Missing "stream" option');
        }

        $handler = new StreamHandler($options['stream']);

        if (isset($options['formatter'])) {
            $handler = $this->applyFormatters($handler, $options['formatter']);
        }

        if (isset($options['properties'])) {
            $this->applyProperties($handler, $options['properties']);
        }

        return $this->applySpecialHandlers($handler, $options);
    }
}
