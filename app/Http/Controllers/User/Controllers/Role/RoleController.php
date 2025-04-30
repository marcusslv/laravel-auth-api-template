<?php

namespace App\Http\Controllers\User\Controllers\Role;

use App\Domains\Role\Services\RoleService;
use App\Http\Controllers\User\Controllers\AbstractController;
use App\Http\Requests\Role\RoleStoreRequest;

class RoleController extends AbstractController
{
    public function __construct(RoleService $service)
    {
        $this->requestValidate = RoleStoreRequest::class;
        $this->requestValidateUpdate = '';
        $this->service = $service;
    }
}
