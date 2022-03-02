<?php

namespace Overphp\LaravelSkeleton\Trace;

use Illuminate\Support\Str;

class TraceID
{
    private static TraceID $instance;

    private string $id;

    /**
     * @param int $length
     * @return string
     */
    public static function id(int $length = 16): string
    {
        return self::instance()->get($length);
    }

    /**
     * @return static
     */
    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param int $length
     * @return string
     */
    public function get(int $length = 16): string
    {
        if (empty($this->id)) {
            $this->id = substr(md5(Str::uuid() . time()), 0, $length);
        }

        return $this->id;
    }

    private function __construct()
    {
    }

    public function __wakeup()
    {
    }

    private function __clone()
    {
    }
}
