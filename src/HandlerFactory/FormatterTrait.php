<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;

use Monolog\Handler\HandlerInterface;

trait FormatterTrait
{
    protected function applyFormatters(HandlerInterface $handler, array $options = []): HandlerInterface
    {
        if (!isset($options['type'])) {
            throw new MissingOptionsException('Missing "type" option');
        }

        $handler->setFormatter(new $options['type'](...$options['arguments']));

        return $handler;
    }
}