<?php

namespace App\Http\Controllers\User;

use App\Domains\User\Services\UserService;
use App\Http\Controllers\AbstractController;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;

class UserController extends AbstractController
{
    public function __construct(UserService $service)
    {
        $this->requestValidate = UserStoreRequest::class;
        $this->requestValidateUpdate = UserUpdateRequest::class;
        $this->service = $service;
    }
}
