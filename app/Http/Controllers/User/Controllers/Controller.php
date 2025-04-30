<?php

namespace App\Http\Controllers\User\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $messageSuccessDefault = 'Operação realizada com sucesso';

    protected $messageErrorDefault = 'Ops';

    const TYPE_SUCCESS = 'success';

    const TYPE_ERROR = 'error';

    /**
     * @param  array  $items
     * @return \Illuminate\Http\JsonResponse
     */
    public function ok($items = [], int $status = Response::HTTP_OK)
    {
        $data = [
            'type' => self::TYPE_SUCCESS,
            'status' => $status,
            'data' => $items,
            'show' => false,
        ];

        return response()->json($data, $status);
    }

    public function error(
        string $message = '',
        array $items = [],
        int $status = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse {
        if (is_null($message)) {
            $message = $this->messageErrorDefault;
        }

        $data = [
            'type' => self::TYPE_ERROR,
            'status' => $status,
            'message' => $message,
            'show' => true,
        ];

        if ($items) {
            foreach ($items as $key => $item) {
                $data['errors'][$key] = $item;
            }
        }

        return response()->json($data, $status);
    }

    /**
     * @param  bool  $showMessage
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(
        string $message,
        array $items = [],
        int $status = Response::HTTP_OK
    ) {
        if (is_null($message)) {
            $message = $this->messageSuccessDefault;
        }

        $data = [
            'type' => self::TYPE_SUCCESS,
            'status' => $status,
            'message' => $message,
            'show' => true,
        ];

        if ($items instanceof Arrayable) {
            $items = $items->toArray();
        }

        if ($items) {
            foreach ($items as $key => $item) {
                $data[$key] = $item;
            }
        }

        return response()->json($data, $status);
    }

    /**
     * @return mixed
     */
    public function getUserAuth()
    {
        return Auth::user();
    }
}
