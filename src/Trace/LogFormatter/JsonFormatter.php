<?php

namespace Overphp\LaravelSkeleton\Trace\LogFormatter;

use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;
use Overphp\LaravelSkeleton\Trace\TraceID;

class JsonFormatter extends BaseJsonFormatter
{
    public const SIMPLE_DATE = 'Y-m-d H:i:s';

    public function format(array $record): string
    {
        // 增加 request_id
        $record['request_id'] = TraceID::id();

        // 去除 extra
        if (isset($record['extra']) && empty($record['extra'])) {
            unset($record['extra']);
        }

        return parent::format($record);
    }
}
