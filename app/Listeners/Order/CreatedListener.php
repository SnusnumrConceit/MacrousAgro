<?php

namespace App\Listeners\Order;

use App\Events\Order\Created;
use App\Notifications\Order\NewOrder;
use App\Notifications\Order\OrderCreated;
use App\User;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class CreatedListener
{
    private $managers;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->managers = User::managers();
    }

    /**
     * Handle the event.
     *
     * @param  Created  $event
     * @return void
     */
    public function handle(Created $event)
    {
        auth()->user()->notify(new OrderCreated($event->order));

        Notification::send($this->managers, new NewOrder($event->order, $event->order->customer));
    }
}
