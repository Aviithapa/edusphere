<?php

namespace App\Services\Permission;

use Illuminate\Http\Request;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class PermissionGetter
 * @package App\Services\Permission
 */
class PermissionGetter
{
    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
     * PermissionGetter constructor.
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get paginated $permission list
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getPaginatedList(Request $request): LengthAwarePaginator
    {
        return $this->permissionRepository->getPaginatedList($request);
    }

    /**
     * Get a single $permission
     * @param $id
     * @return Object|null
     */
    public function show($id)
    {
        return $this->permissionRepository->findOrFail($id);
    }
}