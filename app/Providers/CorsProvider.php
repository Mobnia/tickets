<?php declare(strict_types=1);

namespace App\Providers;



use Tuupola\Middleware\CorsMiddleware;

/**
 * Class CorsProvider
 *
 * @package \App\Providers
 */
class CorsProvider extends AbstractServiceProvider
{

    public function register(): void
    {
        $this->container->singleton(CorsMiddleware::class, function() {
            return new CorsMiddleware();
        });
    }
}