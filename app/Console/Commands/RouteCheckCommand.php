<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;

class RouteCheckCommand extends Command
{
    /** @var int */
    protected const ACTION_PARTS_METHOD = 1;

    /** @var int */
    protected const ACTION_PARTS_CLASS_AND_METHOD = 2;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'route:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check methods of all registered routes';

    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * An array of all the registered routes.
     *
     * @var \Illuminate\Routing\RouteCollection
     */
    protected $routes;

    /**
     * Create a new route command instance.
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct();

        $this->router = $router;
        /** @var \Illuminate\Routing\RouteCollection */
        $routeCollection = $router->getRoutes();
        $this->routes = $routeCollection;
    }

    /**
     * Execute the console command.
     *
     * @return int Exit status.
     */
    public function handle()
    {
        if ($this->routes->count() === 0) {
            $this->error("Your application doesn't have any routes.");

            return self::INVALID;
        }

        $missing = [];

        foreach ($this->getRoutes() as $route) {
            // Closure and ViewController always exist
            if (in_array($route['action'], ['Closure', '\\Illuminate\\Routing\\ViewController'], true)) {
                continue;
            }

            $actionParts = explode('@', $route['action']);

            switch (count($actionParts)) {
                case self::ACTION_PARTS_METHOD:
                    $className = $actionParts[0];
                    if (! class_exists($className) || ! is_callable(new $className)) {
                        $missing[] = [$className.'::__invoke'];

                        continue 2;
                    }
                    break;
                case self::ACTION_PARTS_CLASS_AND_METHOD:
                    $className = $actionParts[0];
                    if (! class_exists($className)) {
                        $missing[] = [$className];

                        continue 2;
                    }
                    if (! is_callable([App::make($className, []), $actionParts[1]])) {
                        $missing[] = [$className.'::'.$actionParts[1]];

                        continue 2;
                    }
                    break;
                default:
                    $missing[] = [$route['action']];

                    continue 2;
            }
        }

        if ($missing !== []) {
            $this->table(['Missing'], $missing);

            return self::FAILURE;
        }

        $this->info('All route methods do exist.');

        return self::SUCCESS;
    }

    /**
     * Compile the routes into a displayable format. FIXME Rename!
     */
    protected function getRoutes(): array
    {
        $results = [];

        foreach ($this->routes as $route) {
            $results[] = $this->getRouteInformation($route);
        }

        return $results;
    }

    /**
     * Get the route information for a given route.
     *
     * @link https://github.com/laravel/framework/blob/9.x/src/Illuminate/Foundation/Console/RouteListCommand.php
     */
    protected function getRouteInformation(Route $route): array
    {
        return ['action' => $route->getActionName()];
    }
}
