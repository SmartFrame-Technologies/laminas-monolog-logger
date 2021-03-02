<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;

use Elasticsearch\ClientBuilder;
use Interop\Container\ContainerInterface;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\HandlerInterface;

class ElasticsearchHandlerFactory implements HandlerFactoryInterface
{
    use SpecialHandlersTrait;
    use FormatterTrait;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HandlerInterface
    {
        $client = ClientBuilder::create()
            ->setHosts($container->get('config')['elasticsearch']['hosts'])
            ->build();

        $handler = new ElasticsearchHandler($client, $options);

        if (isset($options['formatter'])) {
            $handler = $this->applyFormatters($handler, $options['formatter']);
        }

        return $this->applySpecialHandlers($handler, $options);
    }
}