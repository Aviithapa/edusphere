<?php

namespace App\Services\Role;

use App\Repositories\Role\RoleRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class RoleUpdater
{
    /**
     * @var RoleRepository
     */
    protected $roleRepository;

    /**
     * RoleUpdater constructor.
     * @param RoleRepository $roleRepository
     */
    public function __construct(
        RoleRepository $roleRepository,
    ) {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Update an  $Role
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data)
    {
        try {
            $this->roleRepository->findOrFail($id);
            $this->roleRepository->update($id, $data);
            return $this->roleRepository->find($id);
        } catch (ModelNotFoundException $e) {
            Log::warning("Role item with ID {$id} not found: " . $e->getMessage());
            throw new Exception(__("Role item not found."), 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error updating Role item with ID {$id}: " . $e->getMessage());
            throw new Exception(__("An unexpected error occurred while updating the Role item."));
        }
    }

    public function destroy(int $id)
    {
        try {
            $item = $this->roleRepository->findOrFail($id);
            $item->delete();
            return true;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error("Failed to delete Role item with ID {$id}: " . $e->getMessage());
            throw $e;
        }
    }
}