<?php declare(strict_types=1);

namespace App\Providers;


use App\Contracts\ContainerContract;
use Illuminate\Contracts\Container\Container;
/**
 * Class AbstractServiceProvider
 *
 * @package \App\Providers
 */
abstract class AbstractServiceProvider
{
    use ContainerContract;

    public $container;

    public function __construct(Container $container = null)
    {
        $this->container = $container;
    }

    abstract public function register(): void;
}