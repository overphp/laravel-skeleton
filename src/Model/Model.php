<?php

namespace Overphp\LaravelSkeleton\Model;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use DynamicAppendable;

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
