<?php

namespace App\Domains\Abstracts;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Abstracts\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface
{
	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * @return Model
	 */
	public function getModel(): Model
	{
		return $this->model;
	}

	/**
	 * @param $params
	 * @param null $with
	 * @return mixed
	 */
	public function all($params = null, $with = [])
	{
		return $this->getModel()->with($with)->query($params)->paginate(20)->withQueryString();
	}

	/**
	 * Method allWithOutPaginate
	 *
	 * @param $params $params [explicite description]
	 * @param $with $with [explicite description]
	 *
	 * @return void
	 */
	public function allWithOutPaginate($params = null, $with = [])
	{
		return $this->getModel()->with($with)->query($params)->get();
	}

	/**
	 * Retorna em forma de lista para selecte
	 * @return mixed
	 */
	public function list($sortBy = 'name', $pluck = 'name'): array
	{
		return $this->getModel()->all()->sortBy($sortBy)->pluck($pluck, 'id')->all();
	}

	/**
	 * @param $params
	 * @return Model
	 */
	public function create($params): Model
	{
		return $this->getModel()->forceCreate($params);
	}

	/**
	 * @param $id
	 * @param array $with
	 * @return mixed
	 */
	public function find($id, $with = [])
	{
		if (is_numeric($id)) {
			return $this->getModel()->with($with)->find($id);
		}
		return $this->findOneWhere(['uuid' => $id]);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public function findOrFail($id)
	{
		return $this->getModel()->findOrFail($id);
	}

	/**
	 * Usário logado
	 * @param $params
	 * @return mixed
	 */
	public function findByUserAuth(array $params)
	{
		if (isset($params['id_user']) && !empty($params['id_user'])) {
			return $this->findOrFail($params['id_user']);
		}

		return Auth::user()->id;
	}

	/**
	 * @param $id
	 */
	public function delete($id)
	{
		$model = $this->find($id);
		$model->delete();
	}

	/**
	 * @param $entity
	 * @param $data
	 */
	public function update(Model $entity, $data)
	{
        return $entity->forceFill($data)->save();
	}

	/**
	 * @param array $where
	 * @param array $with
	 * @return mixed
	 */
	public function where(array $where, $with = [])
	{
		return $this->getModel()->where($where)->with($with)->get();
	}

	/**
	 * Delete com condição
	 * @param $where
	 */
	public function deleteWhere($where)
	{
		$this->getModel()->where($where)->delete();
	}

	/**
	 * Retorna o primeiro registro encontrado
	 * @param array $where
	 * @return mixed
	 */
	public function findOneWhere(array $where)
	{
		$object = $this->where($where);
		return $object->first();
	}

	/**
	 * Retorna o ID pelo UUID
	 * @param string $uuid
	 * @return mixed
	 */
	public function getIdByUuid(string $uuid)
	{
		return $this->findOneWhere(['uuid' => $uuid])->id;
	}

	/**
	 * getAttribute
	 * @param mixed $value
	 * @return void
	 */
	public function getAttribute($params, $value, $default = null)
	{
		return (isset($params[$value])) ? $params[$value] : $default;
	}
}
