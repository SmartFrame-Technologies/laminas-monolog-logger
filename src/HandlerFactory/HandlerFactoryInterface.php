<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;


use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Monolog\Handler\HandlerInterface;

interface HandlerFactoryInterface extends FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HandlerInterface;

}