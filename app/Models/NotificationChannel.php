<?php

namespace App\Models;

use App\Notifications\NotificationInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

/**
 * @property int $id
 * @property string $provider
 * @property array<string, mixed> $data
 * @property string $label
 * @property bool $connected
 */
class NotificationChannel extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationChannelFactory> */
    use HasFactory;

    use Notifiable;

    protected $fillable = [
        'provider',
        'label',
        'data',
        'connected',
        'is_default',
    ];

    protected $casts = [
        'data' => 'array',
        'connected' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function provider(): \App\NotificationChannels\NotificationChannel
    {
        $class = config('core.notification_channels_providers_class')[$this->provider];

        /** @var \App\NotificationChannels\NotificationChannel $provider */
        $provider = new $class($this);


        return $provider;
    }

    public function notify(NotificationInterface $notification)
    {
        // Find the correct channel class and call send()
        $provider = $this->provider;
        $channelClass = '\\App\\NotificationChannels\\' . ucfirst($provider);

        if (!class_exists($channelClass)) {
            Log::error("Notification channel class $channelClass does not exist.");
            return;
        }

        $channelInstance = new $channelClass($this);
        $channelInstance->send($this, $notification);
    }

    public static function notifyAll(NotificationInterface $notification): void
    {
        $channels = self::all();
        foreach ($channels as $channel) {

            $channel->notify($notification);
        }
    }

}
