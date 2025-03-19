<?php

namespace App\Services\NotificationChannel;

use Illuminate\Http\Request;
use App\Repositories\NotificationChannel\NotificationChannelRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class NotificationChannelGetter
 * @package App\Services\NotificationChannel
 */
class NotificationChannelGetter
{
    /**
     * @var NotificationChannelRepository
     */
    protected $notificationChannelRepository;

    /**
     * NotificationChannelGetter constructor.
     * @param NotificationChannelRepository $notificationChannelRepository
     */
    public function __construct(NotificationChannelRepository $notificationChannelRepository)
    {
        $this->notificationChannelRepository = $notificationChannelRepository;
    }

    /**
     * Get paginated $notificationChannel list
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getPaginatedList(Request $request): LengthAwarePaginator
    {
        return $this->notificationChannelRepository->getPaginatedList($request);
    }

    /**
     * Get a single $notificationChannel
     * @param $id
     * @return Object|null
     */
    public function show($id)
    {
        return $this->notificationChannelRepository->findOrFail($id);
    }
}