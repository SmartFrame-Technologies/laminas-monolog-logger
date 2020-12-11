<?php

declare(strict_types=1);

namespace SmartFrame\Logger\HandlerFactory;

use Elasticsearch\ClientBuilder;
use Interop\Container\ContainerInterface;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Handler\HandlerInterface;

class ElasticsearchHandlerFactory extends AbstractHandlerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): HandlerInterface
    {
        $client = ClientBuilder::create()
            ->setHosts($container->get('config')['elasticsearch']['hosts'])
            ->build();

        //        array(
//            'index' => 'elastic_index_name',
//            'type' => 'elastic_doc_type',
//        );
        $handlerOptions = $container->get('config')['logger']['handlers'][ElasticsearchHandler::class];

        $handler = new ElasticsearchHandler($client, $handlerOptions);

        return $this->applySpecialHandlers($handler, $handlerOptions);
    }
}