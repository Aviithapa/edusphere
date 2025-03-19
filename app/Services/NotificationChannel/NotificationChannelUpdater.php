<?php

namespace App\Services\NotificationChannel;

use App\Repositories\NotificationChannel\NotificationChannelRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class NotificationChannelUpdater
{
    /**
     * @var NotificationChannelRepository
     */
    protected $notificationChannelRepository;

    /**
     * NotificationChannelUpdater constructor.
     * @param NotificationChannelRepository $notificationChannelRepository
     */
    public function __construct(
        NotificationChannelRepository $notificationChannelRepository,
    ) {
        $this->notificationChannelRepository = $notificationChannelRepository;
    }

    /**
     * Update an  $NotificationChannel
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data)
    {
        try {
            $this->notificationChannelRepository->findOrFail($id);
            $this->notificationChannelRepository->update($id, $data);
            return $this->notificationChannelRepository->find($id);
        } catch (ModelNotFoundException $e) {
            Log::warning("NotificationChannel item with ID {$id} not found: " . $e->getMessage());
            throw new Exception(__("NotificationChannel item not found."), 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error updating NotificationChannel item with ID {$id}: " . $e->getMessage());
            throw new Exception(__("An unexpected error occurred while updating the NotificationChannel item."));
        }
    }

    public function destroy(int $id)
    {
        try {
            $item = $this->notificationChannelRepository->findOrFail($id);
            $item->delete();
            return true;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error("Failed to delete NotificationChannel item with ID {$id}: " . $e->getMessage());
            throw $e;
        }
    }
}