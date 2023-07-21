<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LogLoginEvent
{
    /**
     * The request instance.
     */
    protected Request $request;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        if (App::isLocal()) {
            return;
        }

        Log::info(json_encode([
            'event' => Login::class,
            'user_id' => $event->user->id,
            'ip' => $this->request->ip(),
        ]));
    }
}
