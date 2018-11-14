<?php declare(strict_types=1);

use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Zend\Diactoros\ResponseFactory;

$arr = compact("container");
if (!isset($arr["container"])) {
    throw new Exception("No container instance available");
}
$container = $arr['container'];

$responseFactory = new ResponseFactory();
$routerStrategy = (new JsonStrategy($responseFactory))->setContainer($container);
$router = (new Router())->setStrategy($routerStrategy);

$router->get('/', function () {
    return [
        'title'   => 'My New Simple API',
        'version' => 1,
    ];
});

$router->group('/', function ($router) {
    $router->get('/events', '\App\Controllers\Events\EventController::getUpcomingEvents');
    $router->get('/events/{id:number}', '\App\Controllers\Events\EventController::getEvent');
    $router->get('/events/{id:number}/tickets', '\App\Controllers\Tickets\TicketController::getTicketsForEvent');
    $router->put('/tickets/{id:number}', '\App\Controllers\Tickets\TicketController::buyTicket');
    $router->get('/teams', '\App\Controllers\Teams\TeamController::getTeams');
    $router->get('/teams/{id:number}', '\App\Controllers\Teams\TeamController::getTeam');
});

return $router;