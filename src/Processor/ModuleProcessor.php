<?php

declare(strict_types=1);

namespace SmartFrame\Logger\Processor;

use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;

class ModuleProcessor implements ProcessorInterface
{
    private $level;

    private $skipClassesPartials = ['Monolog\\'];

    private $skipStackFramesCount = 0;

    private $skipFunctions = [
        'call_user_func',
        'call_user_func_array',
    ];

    public function __construct($level = Logger::DEBUG, array $skipClassesPartials = [], $skipStackFramesCount = 0)
    {
        $this->level = Logger::toMonologLevel($level);
        $this->skipClassesPartials = array_merge(['Monolog\\'], $skipClassesPartials);
        $this->skipStackFramesCount = $skipStackFramesCount;
    }

    public function __invoke(array $record): array
    {
        // return if the level is not high enough
        if ($record['level'] < $this->level) {
            return $record;
        }

        /*
        * http://php.net/manual/en/function.debug-backtrace.php
        * As of 5.3.6, DEBUG_BACKTRACE_IGNORE_ARGS option was added.
        * Any version less than 5.3.6 must use the DEBUG_BACKTRACE_IGNORE_ARGS constant value '2'.
        */
        $trace = debug_backtrace((PHP_VERSION_ID < 50306) ? 2 : DEBUG_BACKTRACE_IGNORE_ARGS);

        // skip first since it's always the current method
        array_shift($trace);
        // the call_user_func call is also skipped
        array_shift($trace);

        $i = 0;

        while ($this->isTraceClassOrSkippedFunction($trace, $i)) {
            if (isset($trace[$i]['class'])) {
                foreach ($this->skipClassesPartials as $part) {
                    if (strpos($trace[$i]['class'], $part) !== false) {
                        $i++;
                        continue 2;
                    }
                }
            } else if (in_array($trace[$i]['function'], $this->skipFunctions)) {
                $i++;
                continue;
            }

            break;
        }

        $i += $this->skipStackFramesCount;

        if (!isset($trace[$i]['class'])) {
            return $record;
        }

        $record['extra']['module'] = explode('\\', $trace[$i]['class'])[0] ?? null;

        return $record;
    }

    private function isTraceClassOrSkippedFunction(array $trace, $index)
    {
        if (!isset($trace[$index])) {
            return false;
        }

        return isset($trace[$index]['class']) || in_array($trace[$index]['function'], $this->skipFunctions);
    }
}
