<?php

namespace App\Listeners;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Support\Facades\Log;

class LogNotificationFailedEvent
{
    /**
     * Handle the event.
     */
    public function handle(NotificationFailed $event): void
    {
        if (! in_array($event->channel, ['mail', 'vonage']) || $event->notifiable instanceof AnonymousNotifiable) {
            return;
        }

        Log::channel('notification')->info(
            NotificationFailed::class.'::'.json_encode([
                'id' => $event->notifiable->id ?? null,
                'to' => $event->channel === 'vonage' ? $event->notifiable->phone_number : $event->notifiable->email,
                'notification' => get_class($event->notification),
            ])
        );
    }
}
