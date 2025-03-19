<?php

namespace App\NotificationChannels;

use App\Models\NotificationChannel;
use App\Notifications\NotificationInterface;
use App\Web\Pages\Settings\NotificationChannels\Index;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Slack extends AbstractNotificationChannel
{
    public function createRules(array $input): array
    {
        return [
            'webhook_url' => [
                'required',
                'url',
            ],
        ];
    }

    public function createData(array $input): array
    {
        return [
            'webhook_url' => $input['webhook_url'] ?? '',
        ];
    }

    public function data(): array
    {
        return [
            'webhook_url' => $this->notificationChannel->data['webhook_url'] ?? '',
        ];
    }

    public function connect(): bool
    {
        $connect = $this->checkConnection(
            __('Congratulations! 🎉'),
            __("You've connected your Slack to :app", ['app' => config('app.name')])."\n".
            __('Manage your notification channels')
        );

        if (! $connect) {
            $this->notificationChannel->delete();

            return false;
        }

        $this->notificationChannel->connected = true;
        $this->notificationChannel->save();

        return true;
    }

    private function checkConnection(string $subject, string $text): bool
    {
        $connect = Http::post($this->data()['webhook_url'], [
            'text' => '*'.$subject.'*'."\n".$text,
        ]);

        return $connect->ok();
    }

    public function send(object $notifiable, NotificationInterface $notification): void
    {
        Log::info('Slack send method triggered for ' . get_class($notifiable));

        /** @var NotificationChannel $notifiable */
        $this->notificationChannel = $notifiable;
        $data = $this->notificationChannel->data;
        Log::info('Sending Slack message to URL: ' . $data['webhook_url']);

        $response = Http::post($data['webhook_url'], [
            'text' => $notification->toSlack($notifiable),
        ]);

        Log::info('Slack response: ', $response->json());

    }
}
