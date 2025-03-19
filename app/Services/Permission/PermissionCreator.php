<?php

namespace App\Services\Permission;

use App\Repositories\Permission\PermissionRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PermissionCreator
{
    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
     * PermissionCreator constructor.
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        PermissionRepository $permissionRepository,
    ) {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Store an $Permission
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $item = $this->permissionRepository->store($data);
            DB::commit();
            return $item->refresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}