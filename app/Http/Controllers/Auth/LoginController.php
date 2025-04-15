<?php

namespace App\Http\Controllers\Auth;

use App\Domains\User\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function __construct(
        protected UserService $userService,
    )
    {

    }
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->userService->findOneWhere(['email' => $request->email]);

        if (!Hash::check($request->password, $user->password)) {
            return $this->error(
                'Credenciais inválidas!',
                ['message' => 'Credenciais inválidas!'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $token = $user->createToken('admin_token')->plainTextToken;

        return $this->ok([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration'),
        ]);
    }
}
