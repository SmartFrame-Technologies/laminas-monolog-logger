<?php

declare(strict_types=1);

namespace SmartFrame\Logger;

use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SocketHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SmartFrame\Logger\HandlerFactory\ElasticsearchHandlerFactory;
use SmartFrame\Logger\HandlerFactory\RotatingFileHandlerFactory;
use SmartFrame\Logger\HandlerFactory\SocketHandlerFactory;
use SmartFrame\Logger\HandlerFactory\StreamHandlerFactory;
use SmartFrame\Logger\Initializer\LoggerAwareInitializer;
use SmartFrame\Logger\Middleware\RequestLoggerMiddleware;
use SmartFrame\Logger\Processor\ModuleProcessor;
use SmartFrame\Logger\Factory\LoggerAbstractFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                LoggerInterface::class => Logger::class,
            ],
            'factories' => [
                ElasticsearchHandler::class => ElasticsearchHandlerFactory::class,
                StreamHandler::class => StreamHandlerFactory::class,
                SocketHandler::class => SocketHandlerFactory::class,
                RotatingFileHandler::class => RotatingFileHandlerFactory::class,
            ],
            'invokables' => [
                NullLogger::class,
                ModuleProcessor::class,
                RequestLoggerMiddleware::class
            ],
            'initializers' => [
                LoggerAwareInitializer::class,
            ],
            'abstract_factories' => [
                LoggerAbstractFactory::class,
            ],
        ];
    }
}
