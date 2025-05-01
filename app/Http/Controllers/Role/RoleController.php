<?php

namespace App\Http\Controllers\Role;

use App\Domains\Role\Services\RoleService;
use App\Http\Controllers\AbstractController;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\Role\RoleUpdateRequest;

class RoleController extends AbstractController
{
    public function __construct(RoleService $service)
    {
        $this->requestValidate = RoleStoreRequest::class;
        $this->requestValidateUpdate = RoleUpdateRequest::class;
        $this->service = $service;
    }
}
