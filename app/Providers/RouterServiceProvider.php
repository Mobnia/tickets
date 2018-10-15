<?php declare(strict_types=1);

namespace App\Providers;
use Illuminate\Contracts\Container\Container;
use League\Route\Router;


/**
 * Class RouterServiceProvider
 *
 * @package \App\Providers
 */
class RouterServiceProvider extends AbstractServiceProvider
{

    public function register()
    {
        $routeConfig = __DIR__ . '/../../config/routes.php';
        if (!file_exists($routeConfig)) {
            throw new \Exception("No config file round at " . $routeConfig);
        }

        $this->container->singleton(Router::class, function (Container $container) use ($routeConfig) {
            return require_once $routeConfig;
        });

        $this->container->alias(Router::class, "app.router");
    }
}