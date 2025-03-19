<?php

namespace App\NotificationChannels;

use App\Models\NotificationChannel;
use App\Notifications\NotificationInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Sms extends AbstractNotificationChannel
{
    public function createRules(array $input): array
    {
        return [
            'token' => ['required'],
            'url' => ['required', 'url'],
            'from' => ['required', 'string'],
            'dummy_number' => ['required', 'string'],
        ];
    }

    public function createData(array $input): array
    {
        return [
            'token' => $input['token'] ?? '',
            'url' => $input['url'] ?? '',
            'from' => $input['from'] ?? 'TheAlert',
            'dummy_number' => $input['dummy_number'] ?? '',
        ];
    }

    public function data(): array
    {
        return [
            'token' => $this->notificationChannel->data['token'] ?? '',
            'url' => $this->notificationChannel->data['url'] ?? '',
            'from' => $this->notificationChannel->data['from'] ?? 'TheAlert',
            'dummy_number' => $this->notificationChannel->data['dummy_number'] ?? '',
        ];
    }

    public function connect(): bool
    {
        $testMessage = __("You've connected your SMS to :app", ['app' => config('app.name')]);

        $connect = $this->checkConnection('Congratulations! 🎉', $testMessage);

        if (!$connect) {
            $this->notificationChannel->delete();
            return false;
        }

        $this->notificationChannel->connected = true;
        $this->notificationChannel->save();

        return true;
    }

    private function checkConnection(string $subject, string $message): bool
    {
        $data = $this->data();
        if (empty($data['token']) || empty($data['url'])) {
            Log::error('SMS API token or URL missing.');
            return false;
        }

        $params = [
            'token' => $data['token'],
            'from' => $data['from'],
            'to' => $data['dummy_number'], // Replace or pass dynamically
            'text' => $message,
        ];

        $response = Http::post($data['url'], $params);

        if (!$response->successful()) {
            Log::error('SMS connection failed: ' . $response->body());
        }

        return $response->successful();
    }

    public function send(object $notifiable, NotificationInterface $notification): void
    {
        Log::info('SMS send method triggered for ' . get_class($notifiable));

        /** @var NotificationChannel $notifiable */
        $this->notificationChannel = $notifiable;
        $data = $this->notificationChannel->data;

        if (empty($data['token']) || empty($data['url'])) {
            Log::error('SMS API credentials are missing.');
            return;
        }

        $smsData = [
            'token' => $data['token'],
            'from' => $data['from'],
            'to' => $notifiable->phone ?? null, // Ensure the notifiable object has a phone property
            'text' => $notification->toSms($notifiable),
        ];

        if (!$smsData['to']) {
            Log::error('No recipient phone number provided.');
            return;
        }

        $response = Http::post($data['url'], $smsData);

        if (!$response->successful()) {
            Log::error('SMS sending failed: ' . $response->body());
        } else {
            Log::info('SMS sent successfully.');
        }
    }
}
