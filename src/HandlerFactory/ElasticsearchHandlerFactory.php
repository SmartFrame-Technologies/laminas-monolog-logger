<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;

use Elasticsearch\ClientBuilder;
use Interop\Container\ContainerInterface;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;

class ElasticsearchHandlerFactory implements HandlerFactoryInterface
{
    use SpecialHandlersTrait;
    use FormatterTrait;
    use PropertiesTrait;

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HandlerInterface
    {
        if (!isset($container->get('config')['elasticsearch'])) {
            throw new MissingConfigurationException('Missing "elasticsearch" configuration');
        }

        $client = ClientBuilder::create()
            ->setHosts($container->get('config')['elasticsearch']['hosts'])
            ->build();

        $handler = new ElasticsearchHandler($client, $options, isset($options['level']) ? $options['level'] : Logger::DEBUG);

        if (isset($options['formatter'])) {
            $handler = $this->applyFormatters($handler, $options['formatter']);
        }

        if (isset($options['properties'])) {
            $this->applyProperties($handler, $options['properties']);
        }

        return $this->applySpecialHandlers($handler, $options);
    }
}
