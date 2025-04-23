<?php

namespace App\Http\Controllers\Auth;

use App\Domains\User\Services\UserService;
use App\Events\Auth\UserLoggedInEvent;
use App\Events\Auth\UserLoggedOutEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
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
            activity()
                ->useLog('authentication')
                ->event('login_failed')
                ->causedBy($user)
                ->log('Tentativa de login com senha inválida');

            return $this->error(
                'Credenciais inválidas!',
                ['message' => 'Credenciais inválidas!'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        UserLoggedInEvent::dispatch($user);

        return $this->ok([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration'),
        ]);
    }

    public function logout(): JsonResponse
    {
        $user = auth()->user();
        $user->tokens()->delete();

        UserLoggedOutEvent::dispatch($user);

        return $this->success('Logout realizado com sucesso!');
    }
}
