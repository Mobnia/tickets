<?php declare(strict_types=1);

use App\ContainerAdaptor;
use Dotenv\Dotenv;
use Illuminate\Container\Container;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

require_once __DIR__ ."/vendor/autoload.php";
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

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$container = new ContainerAdaptor();
Container::setInstance($container);

$providers = require __DIR__."/config/providers.php";
foreach ($providers as $provider) {
    (new $provider)->setContainer($container)->register();
}

$router = $container->make("app.router");
$request = ServerRequestFactory::fromGlobals();
$request = $request->withParsedBody(json_decode(file_get_contents('php://input')));
$response = new Response();
$emitter = new SapiEmitter();
$container->instance("app.emitter", $emitter);

if ($request->getMethod() === 'OPTIONS') {
    $handler = $container->make(\App\Cors\Middleware\CorsMiddleware::class);
    $response = $handler->handlePreFlightRequest($request, $response);
} else {
    $response = $router->dispatch($request);
}
$emitter->emit($response);
