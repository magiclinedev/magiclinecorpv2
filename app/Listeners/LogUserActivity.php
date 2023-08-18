<?php

namespace App\Listeners;

use App\Models\AuditTrail;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogUserActivity
{
    public function __construct()
    {
        //
    }

    public function handle($event)
    {
        $activity = ($event instanceof Login) ? 'User logged in' : 'User logged out';

        $this->logActivity($event->user, $activity);
    }

    public function logActivity($user, $activity)
    {
        $log = new AuditTrail;
        $log->user_id = $user->id;
        $log->activity = $activity;
        $log->save();
    }
}
