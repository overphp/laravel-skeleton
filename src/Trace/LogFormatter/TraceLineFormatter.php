<?php

namespace Overphp\LaravelSkeleton\Trace\LogFormatter;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;
use Overphp\LaravelSkeleton\Trace\TraceID;

class TraceLineFormatter
{
    /**
     * @param Logger $logger
     */
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $format = "[%datetime%] %channel%.%level_name%." . TraceID::id() . ": %message% %context% %extra%\n";

            $handler->setFormatter(
                new LineFormatter($format, 'Y-m-d H:i:s', true, true)
            );
        }
    }
}
