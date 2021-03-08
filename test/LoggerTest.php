<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger;

use Laminas\ServiceManager\Config;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\ServiceManager;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Monolog\Test\TestCase;
use Psr\Log\LoggerInterface;
use SmartFrame\Logger\ConfigProvider;

class LoggerTest extends TestCase
{
    private ServiceLocatorInterface $serviceManager;

    protected function setUp(): void
    {
        $dependencies = (new ConfigProvider)->getDependencies();

        $container = new ServiceManager();
        (new Config($dependencies))->configureServiceManager($container);

        $this->serviceManager = $container;

        parent::setUp();
    }

    /**
     * @dataProvider minimalServiceProvider
     */
    public function testMinimalConfig(string $service, string $channel): void
    {
        $config = include 'minimal.config.php';
        $this->serviceManager->setService('config', $config);

        $logger = $this->serviceManager->get($service);

        self::assertInstanceOf(LoggerInterface::class, $logger);
        self::assertEquals($channel, $logger->getName());
        self::assertCount(1, $logger->getHandlers());

        $testHandler = new TestHandler();

        //overwrite handler to test output
        $logger->setHandlers([$testHandler]);

        $logger->warning('Foo');
        $logger->error('Bar');

        self::assertCount(2, $testHandler->getRecords());
    }

    /**
     * @dataProvider fullServiceProvider
     */
    public function testFullConfig(string $service, string $channel, int $handlers, int $processors): void
    {
        $config = include 'full.config.php';
        $this->serviceManager->setService('config', $config);

        $logger = $this->serviceManager->get($service);

        self::assertInstanceOf(LoggerInterface::class, $logger);
        self::assertEquals($channel, $logger->getName());
        self::assertCount($handlers, $logger->getHandlers());
        foreach ($logger->getHandlers() as $handler) {
            if ($handler instanceof StreamHandler) {
                self::assertEquals(sprintf('/var/log/%s.log', $channel), $handler->getUrl());
            }
        }
        self::assertCount($processors, $logger->getProcessors());

        $testHandler = new TestHandler();

        //overwrite handler to test output
        $logger->setHandlers([$testHandler]);

        $logger->warning('Foo');
        $logger->error('Bar');

        self::assertCount(2, $testHandler->getRecords());
    }

    public function minimalServiceProvider(): array
    {
        return [
            [Logger::class, 'application']
        ];
    }

    public function fullServiceProvider(): array
    {
        return [
            [LoggerInterface::class, 'php', 1, 1],
            ['ApplicationLogger', 'application', 1, 5],
            ['RequestLogger', 'requests', 1, 5],
            ['ErrorLogger', 'errors', 1, 0],
            ['ConsoleLogger', 'console', 1, 5],
            ['SecurityLogger', 'security', 1, 5],
        ];
    }
}