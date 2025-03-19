<?php

use Illuminate\Support\Facades\Route;
use App\Facades\Notifier;
use App\Http\Controllers\SuperAdmin\NotificationController;
use App\Notifications\SiteInstallationSucceed;

Route::get('/', function () {
    $server = (object) [
        'name' => 'Server 1',
        'ip'   => '127.0.0.1'
    ];
    $notifier =  Notifier::send($server, new SiteInstallationSucceed($server));

    return $notifier;
});


Route::post('/notification', [NotificationController::class, 'store']);
