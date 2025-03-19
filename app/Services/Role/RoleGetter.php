<?php

namespace App\Services\Role;

use Illuminate\Http\Request;
use App\Repositories\Role\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class RoleGetter
 * @package App\Services\Role
 */
class RoleGetter
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * RoleGetter constructor.
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get paginated $role list
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getPaginatedList(Request $request): LengthAwarePaginator
    {
        return $this->roleRepository->getPaginatedList($request);
    }

    /**
     * Get a single $role
     * @param $id
     * @return Object|null
     */
    public function show($id)
    {
        return $this->roleRepository->findOrFail($id);
    }
}