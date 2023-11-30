<?php

namespace App\Listeners;

use Illuminate\Console\Events\ScheduledTaskFinished;
use Illuminate\Support\Facades\Log;

class LogScheduledTaskFinished
{
    /**
     * Handle the event.
     */
    public function handle(ScheduledTaskFinished $event): void
    {
        Log::channel('scheduler')->info(
            ScheduledTaskFinished::class.'::'.json_encode([
                'task' => $event->task->command,
            ])
        );
    }
}
