<?php

namespace App\Domains\Abstracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    public function getModel(): Model
    {
        return $this->model;
    }

    public function all(array $whereParams = [], array $with = [], int $paginationLength = 10): LengthAwarePaginator
    {
        $whereParams = array_filter(
            $whereParams,
            fn ($key) => in_array($key, $this->model->getFillable()),
            ARRAY_FILTER_USE_KEY
        );

        return $this->getModel()->where($whereParams)->with($with)->paginate($paginationLength);
    }

    public function allWithOutPaginate(
        array $whereParams = [],
        array $with = [],
        string $orderBy = 'id',
        string $direction = 'asc'
    ): Collection {
        $whereParams = array_filter(
            $whereParams,
            fn ($key) => in_array($key, $this->model->getFillable()),
            ARRAY_FILTER_USE_KEY
        );

        return $this->getModel()->where($whereParams)->with($with)->orderBy($orderBy, $direction)->get();
    }

    /**
     * Retorna em forma de lista para selecte
     *
     * @return mixed
     */
    public function list($sortBy = 'name', $pluck = 'name'): array
    {
        return $this->getModel()->all()->sortBy($sortBy)->pluck($pluck, 'id')->all();
    }

    public function create($params): Model
    {
        return $this->getModel()->forceCreate($params);
    }

    /**
     * @param  array  $with
     * @return mixed
     */
    public function find($id, $with = [])
    {
        if (is_numeric($id)) {
            return $this->getModel()->with($with)->find($id);
        }

        return $this->findOneWhere(['uuid' => $id]);
    }

    /**
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->getModel()->findOrFail($id);
    }

    /**
     * Usário logado
     *
     * @return mixed
     */
    public function findByUserAuth(array $params)
    {
        if (isset($params['id_user']) && ! empty($params['id_user'])) {
            return $this->findOrFail($params['id_user']);
        }

        return Auth::user()->id;
    }

    public function delete($id)
    {
        $model = $this->find($id);
        $model->delete();
    }

    public function update(Model $entity, $data)
    {
        return $entity->forceFill($data)->save();
    }

    /**
     * @param  array  $with
     * @return mixed
     */
    public function where(array $where, $with = [])
    {
        return $this->getModel()->where($where)->with($with)->get();
    }

    /**
     * Delete com condição
     */
    public function deleteWhere($where)
    {
        $this->getModel()->where($where)->delete();
    }

    /**
     * Retorna o primeiro registro encontrado
     *
     * @return mixed
     */
    public function findOneWhere(array $where)
    {
        $object = $this->where($where);

        return $object->first();
    }

    /**
     * Retorna o ID pelo UUID
     *
     * @return mixed
     */
    public function getIdByUuid(string $uuid)
    {
        return $this->findOneWhere(['uuid' => $uuid])->id;
    }

    /**
     * getAttribute
     *
     * @param  mixed  $value
     * @return void
     */
    public function getAttribute($params, $value, $default = null)
    {
        return (isset($params[$value])) ? $params[$value] : $default;
    }
}
