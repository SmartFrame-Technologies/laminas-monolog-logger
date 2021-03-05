<?php

declare(strict_types=1);

namespace SmartFrameTest\Logger\HandlerFactory;

use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\TestHandler;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use SmartFrame\Logger\HandlerFactory\FormatterTrait;
use SmartFrame\Logger\HandlerFactory\MissingOptionsException;

class FormatterTraitTest extends TestCase
{

    public function testApplyFormatters(): void
    {
        $object = $this->getObjectForTrait(FormatterTrait::class);

        $objectReflection = new ReflectionMethod(get_class($object), 'applyFormatters');
        $objectReflection->setAccessible(true);

        $options = [
            'type' => JsonFormatter::class
        ];

        $handler = new TestHandler();

        //default formatter
        self::assertInstanceOf(LineFormatter::class, $handler->getFormatter());

        $objectReflection->invoke($object, $handler, $options);

        //new formatter
        self::assertInstanceOf(JsonFormatter::class, $handler->getFormatter());
    }

    public function testApplyFormattersMissingType(): void
    {
        $this->expectException(MissingOptionsException::class);

        $object = $this->getObjectForTrait(FormatterTrait::class);

        $objectReflection = new ReflectionMethod(get_class($object), 'applyFormatters');
        $objectReflection->setAccessible(true);

        $options = [];

        $handler = new TestHandler();

        $objectReflection->invoke($object, $handler, $options);
    }

}