<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\Factory;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\ProcessorInterface;
use Monolog\Processor\UidProcessor;
use PHPUnit\Framework\TestCase;
use SmartFrame\Logger\Factory\LoggerFactory;

class LoggerFactoryTest extends TestCase
{

    public function testInvoke(): void
    {
        $containerMock = $this->createMock(ServiceLocatorInterface::class);

        $containerMock
            ->expects(self::once())
            ->method('get')
            ->willReturnMap([
                ['config', [
                    'elasticsearch' => [
                        'hosts' => []
                    ],
                    'logger' => [
                        'channel_name' => 'abc',
                        'handlers' => [
                            ElasticsearchHandler::class => [],
                            StreamHandler::class => ['stream' => '/log/path.log']
                        ],
                        'processors' => [
                            ProcessIdProcessor::class => [],
                            UidProcessor::class => []
                        ]
                    ]]
                ]
            ]);

        $containerMock
            ->expects(self::once())
            ->method('build')
            ->willReturnMap([
                [ElasticsearchHandler::class, [], $this->createMock(HandlerInterface::class)]
            ]);

        $containerMock
            ->expects(self::exactly(4))
            ->method('has')
            ->willReturnMap([
                [ElasticsearchHandler::class, true]
            ]);

        $instance = (new LoggerFactory())->__invoke($containerMock, Logger::class);

        self::assertInstanceOf(Logger::class, $instance);
        self::assertCount(2, $instance->getHandlers());
        foreach ($instance->getHandlers() as $handler) {
            self::assertInstanceOf(HandlerInterface::class, $handler);
        }
        self::assertCount(2, $instance->getProcessors());
        foreach ($instance->getProcessors() as $processor) {
            self::assertInstanceOf(ProcessorInterface::class, $processor);
        }
    }
}
