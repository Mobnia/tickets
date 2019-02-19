<?php declare(strict_types=1);

namespace App\Providers;


use Aura\Filter\FilterFactory;
use Aura\Filter\ValueFilter;

/**
 * Class ValidationProvider
 *
 * @package \App\Providers
 */
class ValidationProvider extends AbstractServiceProvider
{

    public function register(): void
    {
        $filterFactory = new FilterFactory();

        $this->container->singleton(ValueFilter::class, function () use ($filterFactory) {
            return $filterFactory->newValueFilter();
        });
    }
}