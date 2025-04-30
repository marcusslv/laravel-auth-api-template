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
}
