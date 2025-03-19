<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Interface RepositoryInterface
 * @package App\Repositories
 */
interface RepositoryInterface
{
    /**
     * Get all resources
     *
     * @param array $columns
     *
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection;

    /**
     * Get paginated resources
     *
     * @param Request $request
     * @param array $columns
     *
     * @return LengthAwarePaginator|Collection
     */
    public function getPaginatedList(Request $request, $type, array $columns = ['*']);

    /**
     * Stores newly created resource
     *
     * @param array $data
     *
     * @return object
     */
    public function store(array $data): object;

    /**
     * Update specific resource.
     *
     * @param array $data
     * @param       $id
     *
     * @return bool
     */
    public function update($id, array $data): bool;

    /**
     * Delete specific resource
     *
     * @param $id
     *
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Find specific resource
     *
     * @param       $id
     * @param array $columns
     *
     * @return object
     */
    public function find($id, array $columns = ['*']): ?object;

    /**
     * Find specific resource by given attribute
     *
     * @param       $attribute
     * @param       $value
     * @param array $columns
     *
     * @return Object
     */
    public function findBy($attribute, $value, array $columns = ['*']): ?object;

    /**
     * Count specific resource.
     *
     * @return integer
     */
    public function count(): int;


    /**
     * Find specific resource with relations
     *
     * @param int $id
     * @param array $relations
     * @param array $columns
     * @return Object
     */
    public function findWithRelations($id, array $relations = [], array $columns = ['*']): object;

}
