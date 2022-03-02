<?php

namespace Overphp\LaravelSkeleton\Model;

use EloquentFilter\Filterable as EloquentFilterable;

trait Filterable
{
    use EloquentFilterable;

    /**
     * @param $filter
     * @return string
     */
    public function provideFilter($filter = null)
    {
        if ($filter === null) {
            $filter = str_replace(
                config('skeleton.namespace.orm'),
                config('skeleton.namespace.filters'),
                get_called_class()
            );
            $filter .= 'Filter';
        }

        return $filter;
    }
}
