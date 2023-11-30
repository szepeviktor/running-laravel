<?php

namespace App\Listeners;

use Illuminate\Console\Events\ScheduledTaskFailed;
use Illuminate\Support\Facades\Log;

class LogScheduledTaskFailed
{
    /**
     * Handle the event.
     */
    public function handle(ScheduledTaskFailed $event): void
    {
        Log::channel('scheduler')->error(
            ScheduledTaskFailed::class.'::'.json_encode([
                'task' => $event->task->command,
            ])
        );
    }
}
