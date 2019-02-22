<?php declare(strict_types=1);

namespace App\Providers;


use App\Cors\Middleware\CorsMiddleware;
use Neomerx\Cors\Strategies\Settings;

/**
 * Class CorsProvider
 *
 * @package \App\Providers
 */
class CorsProvider extends AbstractServiceProvider
{
    public function register(): void
    {

        $settings = (new Settings())
            ->setServerOrigin('http', 'localhost', 8090)
            ->setPreFlightCacheMaxAge(0)
            ->setCredentialsSupported()
            ->enableAllOriginsAllowed()                                 // or enableAllOriginsAllowed()
            ->setAllowedMethods(['GET', 'POST', 'PUT' , 'OPTIONS'])     // or enableAllMethodsAllowed()
            ->setAllowedHeaders(['Authorization', 'Content-Type'])
            ->setExposedHeaders([])
            ->enableCheckHost()
            ->enableAddAllowedHeadersToPreFlightResponse()
            ->enableAddAllowedMethodsToPreFlightResponse();

        $this->container->singleton(CorsMiddleware::class, function () use ($settings) {
            return new CorsMiddleware($settings);
        });
    }
}
