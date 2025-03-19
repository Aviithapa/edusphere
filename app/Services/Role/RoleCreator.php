<?php

namespace App\Services\Role;

use App\Repositories\Role\RoleRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class RoleCreator
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * RoleCreator constructor.
     * @param RoleRepository $roleRepository
     */
    public function __construct(
        RoleRepository $roleRepository,
    ) {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Store an $Role
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $item = $this->roleRepository->store($data);
            DB::commit();
            return $item->refresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}