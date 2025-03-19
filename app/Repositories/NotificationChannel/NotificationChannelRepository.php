<?php

namespace App\Repositories\NotificationChannel;

use App\Models\NotificationChannel;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationChannelRepository extends Repository
{
    /**
     * NotificationChannelRepository constructor.
     * @param NotificationChannel $notificationChannel
     */
    public function __construct(NotificationChannel $notificationChannel)
    {
        parent::__construct($notificationChannel);
    }

    /**
     * @param Request $request
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function getPaginatedList(Request $request, $type = null, array $columns = ['*']): LengthAwarePaginator
    {
        $limit = $request->get('limit', config('app.per_page'));
        return $this->model->newQuery()
            ->latest()
            ->paginate($limit);
    }
}
