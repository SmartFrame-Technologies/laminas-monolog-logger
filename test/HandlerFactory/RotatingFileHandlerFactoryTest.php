<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\HandlerFactory;

use Interop\Container\ContainerInterface;
use Monolog\Handler\RotatingFileHandler;
use PHPUnit\Framework\TestCase;
use SmartFrame\Logger\HandlerFactory\RotatingFileHandlerFactory;

class RotatingFileHandlerFactoryTest extends TestCase
{

    public function testInvoke(): void
    {
        $containerMock = $this->createMock(ContainerInterface::class);

        $instance = (new RotatingFileHandlerFactory())->__invoke($containerMock, RotatingFileHandler::class, ['stream' => 'data/logs/test.log', 'maxFiles' => 2]);

        self::assertInstanceOf(RotatingFileHandler::class, $instance);
    }
}
