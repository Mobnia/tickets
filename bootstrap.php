<?php declare(strict_types=1);

use App\ContainerAdaptor;
use Illuminate\Container\Container;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require_once dirname(__FILE__)."/vendor/autoload.php";
error_reporting(E_ALL);

$environment = 'development';
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler());
} else {
    $whoops->pushHandler(function($error){
        //TODO: turn error
    });
}
$whoops->register();

$container = new ContainerAdaptor();
Container::setInstance($container);

$providers = require_once __DIR__."/config/providers.php";
foreach ($providers as $provider) {
    (new $provider)->setContainer($container)->register();
}

$router = $container->make("app.router");
$request = ServerRequestFactory::fromGlobals();
$response = new Response();
$emitter = new SapiEmitter();
$container->instance("app.emitter", $emitter);

$response = $router->dispatch($request);
$emitter->emit($response);
