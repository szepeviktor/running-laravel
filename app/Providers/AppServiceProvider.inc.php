<?php

/**
 * Needs configuration item projectconfig.hash => env('APP_HASH', '');
 */

namespace App\Providers;

use Dotenv\Dotenv;
use Exception;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->checkOutdatedWorkers();
    }

    /**
     * Check for queue workers with outdated source code
     */
    protected function checkOutdatedWorkers()
    {
        Queue::before(function (JobProcessing $event) {
            $app = app();

            // @FIXME Compare application source code/git commit hash and .env file contents

            // Re/load .env file
            Dotenv::create(
                Env::getRepository(),
                $app->environmentPath(),
                $app->environmentFile()
            )->safeLoad();

            if (Env::get('APP_HASH') !== config('projectconfig.hash')) {
                $errorMessage = 'Queue workers run with outdated source code!';
                Log::error($errorMessage);
                $event->job->fail(new Exception($errorMessage));
            }
        });
    }
}
