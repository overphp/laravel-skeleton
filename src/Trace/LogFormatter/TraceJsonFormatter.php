<?php

namespace Overphp\LaravelSkeleton\Trace\LogFormatter;

use Illuminate\Log\Logger;

class TraceJsonFormatter
{
    public function __invoke(Logger $logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new JsonFormatter());
        }
    }
}
