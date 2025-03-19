<?php

namespace App\Services\Permission;

use App\Repositories\Permission\PermissionRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PermissionUpdater
{
    /**
     * @var PermissionRepository
     */
    protected $permissionRepository;

    /**
     * PermissionUpdater constructor.
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        PermissionRepository $permissionRepository,
    ) {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Update an  $Permission
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data)
    {
        try {
            $this->permissionRepository->findOrFail($id);
            $this->permissionRepository->update($id, $data);
            return $this->permissionRepository->find($id);
        } catch (ModelNotFoundException $e) {
            Log::warning("Permission item with ID {$id} not found: " . $e->getMessage());
            throw new Exception(__("Permission item not found."), 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error updating Permission item with ID {$id}: " . $e->getMessage());
            throw new Exception(__("An unexpected error occurred while updating the Permission item."));
        }
    }

    public function destroy(int $id)
    {
        try {
            $item = $this->permissionRepository->findOrFail($id);
            $item->delete();
            return true;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error("Failed to delete Permission item with ID {$id}: " . $e->getMessage());
            throw $e;
        }
    }
}