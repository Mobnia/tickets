<?php declare(strict_types=1);


use App\Authentication\Middleware\AuthenticationMiddleware;
use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Zend\Diactoros\ResponseFactory;

$arr = compact("container");
if (!isset($arr["container"])) {
    throw new Exception("No container instance available");
}
$container = $arr['container'];

$responseFactory = new ResponseFactory();
$routerStrategy = new JsonStrategy($responseFactory);
$router = new Router();
$authenticationMiddleware = $container->make(AuthenticationMiddleware::class);

$routerStrategy->setContainer($container);
$router->setStrategy($routerStrategy);


$router->post('/auth/token', '\App\Controllers\Authentication\AuthController::getToken');

$router->group('/', function (RouteGroup $router) use ($authenticationMiddleware) {
    $router->get('/events', '\App\Controllers\Events\EventController::getUpcomingEvents');
    $router->get('/events/{id:number}', '\App\Controllers\Events\EventController::getEvent');
    $router->get('/events/{id:number}/tickets', '\App\Controllers\Tickets\TicketController::getTicketsForEvent');

    $router->put('/tickets/{id:number}', '\App\Controllers\Tickets\TicketPurchaseController::buyTicket');

    $router->get('/teams', '\App\Controllers\Teams\TeamController::getTeams');
    $router->get('/teams/{id:number}', '\App\Controllers\Teams\TeamController::getTeam');

    $router->get('/locations', '\App\Controllers\Locations\LocationController::getLocations');
    $router->get('/locations/{id:number}', '\App\Controllers\Locations\LocationController::getLocation');

    $router->post('/auth/token', '\App\Controllers\Authentication\AuthController::getToken');
})->middleware($authenticationMiddleware);

return $router;