<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\WhatFailureGroupHandler;

abstract class AbstractHandlerFactory implements FactoryInterface
{

    abstract public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HandlerInterface;

    protected function applySpecialHandlers(HandlerInterface $handler, array $options = []): HandlerInterface
    {
        if (isset($options['whatFailure']) && $options['whatFailure'] === true) {
            $handler = new WhatFailureGroupHandler([$handler]);
        }

        if (isset($options['fingersCrossed'])) {
            $handler = new FingersCrossedHandler($handler, new ErrorLevelActivationStrategy($options['fingersCrossed']));
        }

        return $handler;
    }
}