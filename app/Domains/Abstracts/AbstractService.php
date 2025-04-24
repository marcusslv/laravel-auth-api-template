<?php

namespace App\Domains\Abstracts;

use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

abstract class AbstractService implements ServiceInterface
{
    protected $with = [];

    protected $repository;

    /**
     * @return mixed
     */
    public function getAll(array $params = [], array $with = []): LengthAwarePaginator
    {
        $params = $this->cleanPaginationParams($params);

        return $this->getRepository()->all($params, $with);
    }

    private function cleanPaginationParams(array $params): array
    {
        $clean = ['page', 'per_page', 'with', 'without_pagination'];

        return array_diff_key($params, array_flip($clean));
    }

    /**
     * Return all records from the repository without pagination.
     */
    public function getAllWithoutPagination(
        array $params = [],
        array $with = [],
        string $orderBy = 'id',
        string $direction = 'asc'
    ): Collection
    {
        return $this->getRepository()->allWithOutPaginate($params, $with, $orderBy, $direction);
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function find($id, array $with = [])
    {
        $result = $this->repository->find($id, $with);
        if ($result == null) {
            throw new \Exception('Objeto não encontrado na base de dados');
        }

        return $result;
    }

    public function findOneWhere(array $params, array $with = [])
    {
        return $this->repository->findOneWhere($params, $with);
    }

    public function beforeSave(array $data): array
    {
        return $data;
    }

    /**
     * @return array
     */
    public function beforeUpdate($id, array $data)
    {
        return $data;
    }

    /**
     * @return mixed
     */
    public function save(array $data)
    {
        $data = $this->beforeSave($data);
        if ($this->validateOnInsert($data) !== false) {
            $entity = $this->repository->create($data);
            $this->afterSave($entity, $data);

            return $entity;
        }
    }

    public function afterSave($entity, array $params)
    {
        return $entity;
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function update($id, array $data): void
    {
        $data = $this->beforeUpdate($id, $data);
        $data = $this->validateOnUpdate($id, $data);
        $entity = $this->find($id);
        $this->repository->update($entity, $data);
        $this->afterUpdate($entity, $data);
    }

    public function afterUpdate($entity, array $params) {}

    /**
     * @return mixed
     */
    public function beforeDelete($id)
    {
        return $id;
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    public function delete($id)
    {
        $this->validateOnDelete($id);
        $this->beforeDelete($id);
        $this->repository->delete($id);
        $this->afterDelete($id);

        return $id;
    }

    /**
     * @return mixed
     */
    public function afterDelete($id)
    {
        return $id;
    }

    /**
     * @return bool
     */
    public function validateOnInsert(array $params)
    {
        return $params;
    }

    public function validateOnUpdate($id, array $params): array
    {
        return $params;
    }

    /**
     * @throws \Exception
     */
    public function validateOnDelete($id)
    {
        $result = $this->repository->find($id);
        if ($result == null) {
            throw new \Exception('Objeto não encontrado na base de dados');
        }
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return mixed
     */
    public function getUserAuth()
    {
        return Auth::user();
    }

    /**
     * @param  string  $message
     */
    public function makeRequestExterna(string $url, string $messageComparation): bool
    {
        $response = Http::get($url);

        return
            $response->status() === Response::HTTP_OK &&
            $response->json()['message'] == $messageComparation;
    }

    /**
     * Pre Requisite default
     */
    public function preRequisite($id = null) {}

    /**
     * Simples criação, sem validações
     *
     * @return mixed
     */
    public function create(array $data)
    {
        $entity = $this->repository->create($data);
        $this->afterSave($entity, $data);

        return $entity;
    }
}
