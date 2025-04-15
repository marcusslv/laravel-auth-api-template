<?php

namespace App\Domains\User\Repositories;

use App\Domains\Abstracts\AbstractRepository;
use App\Models\User;

class UserRepository extends AbstractRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
