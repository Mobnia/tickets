<?php declare(strict_types=1);

namespace App\Controllers\Events;


use App\Controllers\BaseController;
use App\Models\Event;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest as Request;

/**
 * Class EventController
 *
 * @package \App\Controllers\Events
 */
class EventController extends BaseController
{
    private $event;

    public function __construct(Request $request, Response $response, Event $event)
    {
        $this->event = $event;
        parent::__construct($request, $response);
    }

    public function getUpcomingEvents()
    {
        $events = $this->event::all();

        foreach ($events as $event) {
            $event->homeTeam;
            $event->awayTeam;
            $event->location;
        }

        return $this->convertObjectToArray($events);
    }

}