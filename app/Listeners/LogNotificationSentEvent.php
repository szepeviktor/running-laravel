<?php

namespace App\Listeners;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;

class LogNotificationSentEvent
{
    /**
     * Handle the event.
     */
    public function handle(NotificationSent $event): void
    {
        if (! in_array($event->channel, ['mail', 'vonage'])) {
            return;
        }

        Log::channel('notification')->info(
            NotificationSent::class.'::'.json_encode([
                'id' => $event->notifiable instanceof AnonymousNotifiable ? null : $event->notifiable->getKey(),
                'to' => $event->notifiable->routeNotificationFor($event->channel),
                'notification' => get_class($event->notification),
            ])
        );
    }
}
