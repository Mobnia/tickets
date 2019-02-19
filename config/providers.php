<?php declare(strict_types=1);

use App\Providers\AuthenticationProvider;
use App\Providers\CorsProvider;
use App\Providers\DatabaseProvider;
use App\Providers\RouterServiceProvider;
use App\Providers\ValidationProvider;

return [
    RouterServiceProvider::class,
    DatabaseProvider::class,
    ValidationProvider::class,
    AuthenticationProvider::class,
    CorsProvider::class
];
