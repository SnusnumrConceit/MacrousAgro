<?php

namespace App\Listeners\User;

use App\User;
use App\Events\User\Registered;
use App\Notifications\Register\NewUser;
use App\Notifications\Register\Welcome;
use Illuminate\Support\Facades\Notification;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\InteractsWithQueue;

class RegisteredListener
{
    private $admins;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->admins = User::admins();
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $event->user->notify(new Welcome($event->user));

        Notification::send($this->admins, new NewUser($event->user));
    }
}
