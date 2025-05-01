<?php

namespace App\Domains\Role\Services;

use App\Domains\Abstracts\AbstractService;
use App\Domains\Role\Repositories\RoleRepository;

class RoleService extends AbstractService
{
    public function __construct(RoleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validateOnUpdate($id, array $params): array
    {
        if (empty($params['name'])) {
            unset($params['name']);
        }

        if (empty($params['guard_name'])) {
            unset($params['guard_name']);
        }

        if (empty($params['description'])) {
            unset($params['description']);
        }

        return $params;
    }
}
