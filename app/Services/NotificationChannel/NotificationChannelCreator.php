<?php

namespace App\Services\NotificationChannel;

use App\Repositories\NotificationChannel\NotificationChannelRepository;
use Exception;
use Illuminate\Validation\ValidationException;

class NotificationChannelCreator
{
    /**
     * @var NotificationChannelRepository
     */
    protected $notificationChannelRepository;

    /**
     * NotificationChannelCreator constructor.
     * @param NotificationChannelRepository $notificationChannelRepository
     */
    public function __construct(
        NotificationChannelRepository $notificationChannelRepository,
    ) {
        $this->notificationChannelRepository = $notificationChannelRepository;
    }

    /**
     * Store an $NotificationChannel
     * @param array $input
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $input)
    {
        $channel =  $this->notificationChannelRepository->store([
            'provider' => $input['provider'],
            'label' => $input['label'],
        ]);
        $channel->data = $channel->provider()->createData($input);
        $channel->save();

        try {
            if (! $channel->provider()->connect()) {
                $channel->delete();

                if ($channel->provider === \App\Enums\NotificationChannel::EMAIL) {
                    throw ValidationException::withMessages([
                        'email' => __('Could not connect! Make sure you configured `.env` file correctly.'),
                    ]);
                }

                throw ValidationException::withMessages([
                    'provider' => __('Could not connect'),
                ]);
            }
        } catch (Exception $e) {
            $channel->delete();

            throw ValidationException::withMessages([
                'provider' => $e->getMessage(),
            ]);
        }

        $channel->connected = true;
        $channel->save();
    }
}
