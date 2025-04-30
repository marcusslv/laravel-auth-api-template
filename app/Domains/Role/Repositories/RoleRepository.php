<?php

namespace App\Domains\Role\Repositories;

use App\Domains\Abstracts\AbstractRepository;
use Spatie\Permission\Models\Role;

class RoleRepository extends AbstractRepository
{
    public function __construct(Role $model)
    {
        $this->model = $model;
    }
}
