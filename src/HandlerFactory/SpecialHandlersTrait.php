<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;

use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\WhatFailureGroupHandler;

trait SpecialHandlersTrait
{
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