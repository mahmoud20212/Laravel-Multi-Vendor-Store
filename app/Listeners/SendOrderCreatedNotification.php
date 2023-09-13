<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOrderCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $user = User::where('store_id', $event->order->store_id)->first();
        // dd($user);
        $user->notify(new OrderCreatedNotification($event->order));

        // $users = User::where('store_id', $event->order->store_id)->get();
        // Notification::send($users, new OrderCreatedNotification($event->order));
    }
}
