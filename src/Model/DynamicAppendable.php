<?php

namespace Overphp\LaravelSkeleton\Model;

trait DynamicAppendable
{
    /**
     * Dynamic append fields
     *
     * @var array
     */
    protected array $dynamicAppends = [];

    /**
     * @return array
     */
    protected function getArrayableAppends(): array
    {
        $this->addDynamicAppends();

        return parent::getArrayableAppends();
    }

    /**
     * @return void
     */
    protected function addDynamicAppends()
    {
        if (count($this->dynamicAppends)) {
            $keys = array_keys($this->attributes);
            foreach ($this->dynamicAppends as $key => $values) {
                if (!is_array($values)) {
                    $values = [$values];
                }

                if (count(array_diff($values, $keys)) === 0) {
                    $this->append($key);
                }
            }
        }
    }
}