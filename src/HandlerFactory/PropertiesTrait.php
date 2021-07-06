<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;

use Monolog\Handler\HandlerInterface;

trait PropertiesTrait
{
    protected function applyProperties(HandlerInterface $handler, array $properties): void
    {
        foreach ($properties as $property => $value) {
            $setter = sprintf('set%s', ucfirst($property));
            if (!method_exists($handler, $setter)) {
                throw new MissingMethodException(sprintf('Property %s is not settable in %s', $property, get_class($handler)));
            }
            $handler->$setter($value);
        }
    }
}