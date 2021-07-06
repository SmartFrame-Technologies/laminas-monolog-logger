<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\HandlerFactory;

use Error;
use Monolog\Handler\SocketHandler;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use SmartFrame\Logger\HandlerFactory\PropertiesTrait;

class PropertiesTraitTest extends TestCase
{
    public function testApplyProperties(): void
    {
        $object = $this->getObjectForTrait(PropertiesTrait::class);

        $objectReflection = new ReflectionMethod(get_class($object), 'applyProperties');
        $objectReflection->setAccessible(true);

        $handler = new SocketHandler('tcp://test:1000');

        $connectionTimeoutBefore = $handler->getConnectionTimeout();
        $objectReflection->invoke($object, $handler, ['connectionTimeout' => $connectionTimeoutBefore + 1]);
        self::assertNotEquals($connectionTimeoutBefore, $handler->getConnectionTimeout());
    }

    public function testExceptionWhenApplyProperties(): void
    {
        $property = 'undefinedProperty';

        $this->expectException(Error::class);
        $this->expectExceptionMessage(sprintf('Call to undefined method %s::set%s()', SocketHandler::class, ucfirst($property)));

        $object = $this->getObjectForTrait(PropertiesTrait::class);

        $objectReflection = new ReflectionMethod(get_class($object), 'applyProperties');
        $objectReflection->setAccessible(true);

        $handler = new SocketHandler('tcp://test:1000');

        $objectReflection->invoke($object, $handler, [$property => true]);
    }
}