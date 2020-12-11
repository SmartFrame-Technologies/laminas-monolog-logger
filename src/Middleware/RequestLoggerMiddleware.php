<?php

declare(strict_types=1);

namespace SmartFrame\Logger\Middleware;

use Laminas\Diactoros\Request\ArraySerializer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class RequestLoggerMiddleware implements MiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->logger->info('request', ArraySerializer::toArray($request));

        return $handler->handle($request);
    }
}
