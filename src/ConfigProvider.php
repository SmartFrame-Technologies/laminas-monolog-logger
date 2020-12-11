<?php

declare(strict_types=1);

namespace SmartFrame\Logger;

use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SmartFrame\Logger\Factory\LoggerFactory;
use SmartFrame\Logger\HandlerFactory\ElasticsearchHandlerFactory;
use SmartFrame\Logger\HandlerFactory\StreamHandlerFactory;
use SmartFrame\Logger\Middleware\RequestLoggerMiddleware;
use SmartFrame\Logger\Processor\ModuleProcessor;

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
                Logger::class => LoggerFactory::class,
                ElasticsearchHandler::class => ElasticsearchHandlerFactory::class,
                StreamHandler::class => StreamHandlerFactory::class
            ],
            'invokables' => [
                NullLogger::class,
                ModuleProcessor::class,
                RequestLoggerMiddleware::class
            ],
        ];
    }
}