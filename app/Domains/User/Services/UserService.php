<?php

namespace App\Domains\User\Services;

use App\Domains\Abstracts\AbstractService;
use App\Domains\User\Repositories\UserRepository;
use App\Events\User\UserCreatedEvent;
use App\Events\User\UserUpdatedEvent;
use Illuminate\Support\Facades\Hash;

class UserService extends AbstractService
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function beforeSave(array $data): array
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        unset($data['password_confirmation']);

        return $data;
    }

    public function afterSave($entity, array $params)
    {
        UserCreatedEvent::dispatch($entity);

        return $entity;
    }

    public function afterUpdate($entity, array $params): void
    {
        UserUpdatedEvent::dispatch($entity);
    }

    public function validateOnDelete($id)
    {
        $result = $this->repository->find($id);
        if ($result == null) {
            throw new \Exception('Objeto não encontrado na base de dados', 404);
        }

        if ($result->id == auth()->user()->id) {
            throw new \Exception('Você não pode excluir a si mesmo', 403);
        }
    }
}
