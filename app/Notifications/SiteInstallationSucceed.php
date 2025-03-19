<?php

namespace App\Notifications;



class SiteInstallationSucceed extends AbstractNotification
{

    public function __construct(protected Object $server) {}

    public function rawText(): string
    {
        return "Installation succeeded for site: {$this->server->name} (IP: {$this->server->ip})";
    }

}
