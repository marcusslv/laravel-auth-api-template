<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

abstract class AbstractController extends Controller
{
    protected $with = [];

    protected $service;

    protected $requestValidate;

    protected $requestValidateUpdate;

    public function index(Request $request): JsonResponse
    {
        if (isset($request->with)) {
            $this->with = $request->with;
        }

        $withoutPagination = filter_var(
            $request->get('without_pagination', false),
            FILTER_VALIDATE_BOOLEAN
        );
        $direction = $request->get('direction', 'asc');
        $orderBy = $request->get('order_by', 'id');

        if ($withoutPagination) {
            $items = $this->service->getAllWithoutPagination($request->all(), $this->with, $orderBy, $direction);

            return $this->ok($items->toArray());
        }

        $items = $this
            ->service
            ->getAll($request->all(), $this->with);

        return $this->ok($items->toArray());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            if ($this->requestValidate) {
                $requestValidate = app($this->requestValidate);
                $request->validate($requestValidate->rules());
            }
        } catch (ValidationException $e) {
            return $this->error($this->messageErrorDefault, $e->errors());
        }

        try {
            DB::beginTransaction();
            $response = $this->service->save($request->all());
            DB::commit();

            return $this->success($this->messageSuccessDefault, ['response' => $response]);
        } catch (\Exception|ValidationException $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return $this->error($this->messageErrorDefault, $e->errors());
            }
            if ($e instanceof \Exception) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            if (! empty($this->requestValidateUpdate)) {
                $requestValidateUpdate = app($this->requestValidateUpdate);
                $request->validate($requestValidateUpdate->rules());
            }
        } catch (ValidationException $e) {
            return $this->error($this->messageErrorDefault, $e->errors());
        }

        try {
            DB::beginTransaction();
            $this->service->update($id, $request->all());
            DB::commit();

            return $this->success($this->messageSuccessDefault);
        } catch (\Exception|ValidationException $e) {
            DB::rollBack();
            if ($e instanceof \Exception) {
                return $this->error($e->getMessage());
            }
            if ($e instanceof ValidationException) {
                return $this->error($this->messageErrorDefault, $e->errors());
            }
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            return $this->ok($this->service->find($id, $this->with));
        } catch (\Exception|ValidationException $e) {
            DB::rollBack();
            if ($e instanceof \Exception) {
                return $this->error($e->getMessage());
            }
            if ($e instanceof ValidationException) {
                return $this->error($this->messageErrorDefault, $e->errors());
            }
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->service->delete($id);

            return $this->success($this->messageSuccessDefault);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), [], $e->getCode());
        }
    }

    /**
     * @param  null  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function preRequisite($id = null)
    {
        $preRequisite = $this->service->preRequisite($id);

        return $this->ok(compact('preRequisite'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function toSelect()
    {
        return $this->ok($this->service->toSelect());
    }
}
