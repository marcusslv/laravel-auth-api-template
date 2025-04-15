<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\AbstractController;
use App\Domains\User\Services\UserService;


class UserController extends AbstractController
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
}
