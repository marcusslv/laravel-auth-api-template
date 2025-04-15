<?php

namespace App\Domains\User\Services;

use App\Domains\User\Repositories\UserRepository;
use App\Domains\Abstracts\AbstractService;

class UserService extends AbstractService
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
}
