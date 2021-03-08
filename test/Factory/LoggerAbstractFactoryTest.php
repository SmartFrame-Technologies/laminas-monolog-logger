<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\Factory;


use GuzzleHttp\Ring\Client\CurlHandler;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\UidProcessor;
use PHPUnit\Framework\TestCase;
use SmartFrame\Logger\Factory\LoggerAbstractFactory;

class LoggerAbstractFactoryTest extends TestCase
{
    /**
     * @dataProvider canCreateDataProvider
     */
    public function testCanCreate(array $config, string $requestedName, bool $result): void
    {
        $factory = new LoggerAbstractFactory();
        $container = $this->createMock(ContainerInterface::class);

        $container
            ->method('get')
            ->willReturn($config);

        self::assertEquals($result, $factory->canCreate($container, $requestedName));
    }

    /**
     * @dataProvider cannotCreateDataProvider
     */
    public function testCannotCreate(string $requestedName): void
    {
        $this->expectException(ServiceNotFoundException::class);
        $this->expectExceptionMessage(sprintf(
            'Unable to resolve service "%s" to a factory; this service name is reserved.',
            $requestedName
        ));

        $factory = new LoggerAbstractFactory();
        $container = $this->createMock(ContainerInterface::class);

        $factory->canCreate($container, $requestedName);
    }

    public function testFactorySharedConfiguration(): void
    {
        $factory = new LoggerAbstractFactory();
        $config = [
            'logger' => [
                'processors' => [
                    ProcessIdProcessor::class => [],
                ],
                'handlers' => [
                    StreamHandler::class => ['/var/log/application.log']
                ],
                Logger::class => [
                    'channel_name' => 'default'
                ],
                'ErrorLogger' => [
                    'channel_name' => 'errors'
                ],
                'ApplicationLogger' => [
                    'channel_name' => 'application'
                ],
                'MetricsLogger' => [
                    'channel_name' => 'metrics'
                ],
            ]
        ];


        $container = $this->createMock(ContainerInterface::class);
        $container
            ->method('get')
            ->willReturn($config);

        $requestedName = Logger::class;

        $logger = $factory($container, $requestedName);

        $processors = $logger->getProcessors();
        self::assertCount(1, $processors);
        self::assertInstanceOf(ProcessIdProcessor::class, $processors[0]);

        $handlers = $logger->getHandlers();
        self::assertCount(1, $handlers);
        self::assertInstanceOf(StreamHandler::class, $handlers[0]);

        self::assertEquals('default', $logger->getName());
    }

    public function testFactoryPerServiceConfiguration(): void
    {
        $factory = new LoggerAbstractFactory();
        $config = [
            'logger' => [
                'processors' => [
                    ProcessIdProcessor::class => [],
                ],
                'handlers' => [
                    StreamHandler::class => ['/var/log/application.log']
                ],
                Logger::class => [
                    'channel_name' => 'default'
                ],
                'ErrorLogger' => [
                    'channel_name' => 'errors',
                    'processors' => [
                        UidProcessor::class => [],
                    ],
                ],
                'ApplicationLogger' => [
                    'channel_name' => 'application',
                ],
                'MetricsLogger' => [
                    'channel_name' => 'metrics',
                    'handlers' => [
                        CurlHandler::class => []
                    ],
                ],
            ]
        ];


        $container = $this->createMock(ContainerInterface::class);
        $container
            ->method('get')
            ->willReturn($config);

        //service 1
        $logger = $factory($container, Logger::class);

        $processors = $logger->getProcessors();
        self::assertCount(1, $processors);
        self::assertInstanceOf(ProcessIdProcessor::class, $processors[0]);

        $handlers = $logger->getHandlers();
        self::assertCount(1, $handlers);
        self::assertInstanceOf(StreamHandler::class, $handlers[0]);

        self::assertEquals('default', $logger->getName());

        //service 2
        $logger = $factory($container, 'ErrorLogger');

        $processors = $logger->getProcessors();
        self::assertCount(1, $processors);
        self::assertInstanceOf(UidProcessor::class, $processors[0]);

        $handlers = $logger->getHandlers();
        self::assertCount(1, $handlers);
        self::assertInstanceOf(StreamHandler::class, $handlers[0]);

        self::assertEquals('errors', $logger->getName());
    }

    public function canCreateDataProvider(): array
    {
        return [
            [
                [
                    'logger' => [
                        'MyLogger' => []
                    ]
                ],
                'MyLogger',
                true
            ],
            [
                [
                    'logger' => [
                        'ErrorLogger' => []
                    ],
                ],
                'MyLogger',
                false
            ],
            [
                [],
                'Logger',
                false
            ]
        ];
    }

    public function cannotCreateDataProvider(): array
    {
        return [
            ['handlers'],
            ['processors'],
        ];
    }
}