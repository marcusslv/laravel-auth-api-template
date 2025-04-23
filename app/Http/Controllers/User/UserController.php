<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractController;
use App\Domains\User\Services\UserService;
use App\Http\Requests\User\UserStoreRequest;


class UserController extends AbstractController
{
    public function __construct(UserService $service)
    {
        $this->requestValidate = UserStoreRequest::class;
        $this->service = $service;
    }
}
