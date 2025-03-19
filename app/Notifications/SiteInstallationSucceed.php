<?php

namespace App\Notifications;



class SiteInstallationSucceed extends AbstractNotification
{

    public function rawText(): string
    {
        return __('Installation succeed for site');
    }

    public function toSlack(object $notifiable): string
    {
        return 'This is a test Slack notification from Laravel!';
    }

}
