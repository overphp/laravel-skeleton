<?php

namespace Overphp\LaravelSkeleton\Logic;

use Overphp\LaravelSkeleton\Model\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Overphp\LaravelSkeleton\Pagination\PaginationQuery;

abstract class CurdLogic extends Logic
{
    protected Model|null $modelInstance = null;

    /**
     * @return string
     */
    abstract protected function orm(): string;

    /**
     * @return Model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function model(): Model
    {
        if ($this->modelInstance === null) {
            $this->modelInstance = app()->make($this->orm());

            if (!$this->modelInstance instanceof Model) {
                throw new \Exception(
                    "Class {$this->orm()} must be an instance of Overphp\\Skeleton\\Model\\Model"
                );
            }
        }

        return $this->modelInstance;
    }

    /**
     * @param array $params
     * @return EloquentBuilder|QueryBuilder
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function listQueryBuilder(array $params = []): EloquentBuilder|QueryBuilder
    {
        return $this->model()->filter($params)->orderBy('id', 'desc');
    }

    /**
     * @param array $params
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getPaginationList(array $params = []): array
    {
        return (new PaginationQuery())->query($this->listQueryBuilder($params))->toArray();
    }

    /**
     * @param array $params
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getList(array $params = []): array
    {
        return $this->listQueryBuilder($params)->get()->toArray();
    }

    /**
     * create
     *
     * @param array $data
     * @return Model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function create(array $data): Model
    {
        return $this->model()->create($data);
    }

    /**
     * update
     *
     * @param array $data
     * @return Model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function update(array $data): Model
    {
        $model = $this->model()->findOrFail($data['id']);
        $model->update($data);

        return $model;
    }

    /**
     * delete
     *
     * @param $id
     * @return int
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function delete($id): int
    {
        return $this->model()->destroy($id);
    }

    /**
     * batch delete
     *
     * @param array $ids
     * @return int
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function batchDelete(array $ids): int
    {
        return $this->model()->destroy($ids);
    }

    /**
     * @param $id
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getDetail($id): array
    {
        return $this->model()->findOrFail($id)->toArray();
    }
}
