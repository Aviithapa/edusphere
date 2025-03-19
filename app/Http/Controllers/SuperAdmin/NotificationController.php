<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Resources\Notification\NotificationResource;
use App\Services\NotificationChannel\NotificationChannelCreator;
use App\Services\NotificationChannel\NotificationChannelGetter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, NotificationChannelGetter $notificationChannelGetter): AnonymousResourceCollection
    {
        return NotificationResource::collection($notificationChannelGetter->getPaginatedList($request));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, NotificationChannelCreator $notificationChannelCreator)
    {
        $data = $request->all();
        return $this->successResponse(
            NotificationResource::make($notificationChannelCreator->store($data)),
            'Notification channel has been created successfully',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
