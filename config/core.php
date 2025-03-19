<?php

return [
    /*
     * Notification channels
     */
    'notification_channels_providers' => [
        \App\Enums\NotificationChannel::SLACK,
        \App\Enums\NotificationChannel::DISCORD,
        \App\Enums\NotificationChannel::EMAIL,
        \App\Enums\NotificationChannel::TELEGRAM,
    ],
    'notification_channels_providers_class' => [
        \App\Enums\NotificationChannel::SLACK => \App\NotificationChannels\Slack::class,
        \App\Enums\NotificationChannel::DISCORD => \App\NotificationChannels\Discord::class,
        \App\Enums\NotificationChannel::EMAIL => \App\NotificationChannels\Email::class,
        \App\Enums\NotificationChannel::TELEGRAM => \App\NotificationChannels\Telegram::class,
    ],
];
