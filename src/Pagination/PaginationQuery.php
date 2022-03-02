<?php

namespace Overphp\LaravelSkeleton\Pagination;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PaginationQuery
{
    protected Collection $collection;
    protected Pagination|null $pagination;
    protected string $list_key = 'list';

    /**
     * @param Pagination|null $pagination
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(Pagination|null $pagination = null)
    {
        if ($pagination === null) {
            $this->pagination = new Pagination(0);
        }
    }

    /**
     * @param EloquentBuilder|QueryBuilder $builder
     * @param array $columns
     * @return $this
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function query(EloquentBuilder|QueryBuilder $builder, array $columns = ['*']): self
    {
        if ($builder->getQuery()->groups || $builder->getQuery()->havings) {
            $total = $builder->getQuery()->getCountForPagination();
        } else {
            $total = $builder->count();
        }

        $this->pagination->paginate($total);

        $this->collection = $builder->skip($this->pagination->skip())
            ->take($this->pagination->take())
            ->get($columns);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setListKey(string $key): self
    {
        $this->list_key = $key;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'pagination' => $this->pagination->toArray(),
            $this->list_key => $this->collection->toArray(),
        ];
    }
}
