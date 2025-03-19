<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class BaseRepository
 * @package App\Repositories\Eloquent
 */
class Repository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all resources
     *
     * @param array $columns
     *
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Store newly created resource
     *
     * @param array $data
     *
     * @return Model
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update specific resource.
     *
     * @param array $data
     * @param       $id
     *
     * @return bool
     */
    public function update($id, array $data): bool
    {
        return $this->model->find($id)->update($data);
    }

    /**
     * Update or create specific resource.
     *
     * @param array $attributes
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $data)
    {
        return $this->model->updateOrCreate($attributes, $data);
    }

    /**
     * Delete specific resource
     *
     * @param $id
     *
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->model->destroy($id);
    }

    /**
     * Find specific resource
     *
     * @param       $id
     * @param array $columns
     *
     * @return Object
     */
    public function find($id, array $columns = ['*']): ?object
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find or Fail specific resource
     *
     * @param       $id
     * @param array $columns
     *
     * @return Object
     */
    public function findOrFail($id, array $columns = ['*']): object
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Find specific resource by given attribute
     *
     * @param       $attribute
     * @param       $value
     * @param array $columns
     *
     * @return Object
     */
    public function findBy($attribute, $value, array $columns = ['*']): ?object
    {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * Get paginated resources
     *
     * @param Request $request
     * @param array $columns
     *
     * @return Collection|Model[]
     */
    public function getPaginatedList(Request $request, $type, array $columns = ['*'])
    {
        return $this->model->where('type', '=', $type)->paginate();
    }

    /**
     * Count model
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Insert data into  model
     */
    public function insert($data)
    {
        return $this->model->insert($data);
    }

    /**
     * Find data by specified column name and value.
     *
     * @param string $key
     * @param string $value
     * @param string $operator
     *
     * @param bool $paginate
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByWithPagination($key, $value, $length = 10, $operator = '=')
    {
        return $this->model->where($key, $operator, $value)->orderBy('created_at', 'desc')->paginate($length);
    }

    public function paginatedList($length = 10)
    {
        return $this->model->orderBy('created_at', 'desc')->paginate($length);
    }

    /**
     * Find specific resource with relations
     *
     * @param int $id
     * @param array $relations
     * @param array $columns
     * @return Object
     */
    public function findWithRelations($id, array $relations = [], array $columns = ['*']): object
    {
        return $this->model->with($relations)->findOrFail($id, $columns);
    }


}
