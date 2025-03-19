<?php

namespace App\Services\User;

use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserUpdater
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserUpdater constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Update an  $User
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data)
    {
        try {
            $this->userRepository->findOrFail($id);
            $this->userRepository->update($id, $data);
            return $this->userRepository->find($id);
        } catch (ModelNotFoundException $e) {
            Log::warning("User item with ID {$id} not found: " . $e->getMessage());
            throw new Exception(__("User item not found."), 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error updating User item with ID {$id}: " . $e->getMessage());
            throw new Exception(__("An unexpected error occurred while updating the User item."));
        }
    }

    public function destroy(int $id)
    {
        try {
            $item = $this->userRepository->findOrFail($id);
            $item->delete();
            return true;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error("Failed to delete User item with ID {$id}: " . $e->getMessage());
            throw $e;
        }
    }
}
