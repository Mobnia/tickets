<?php declare(strict_types=1);

namespace App\Controllers\Events;


use App\Controllers\BaseController;
use App\Models\Event;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest as Request;
use Zend\Diactoros\ServerRequest;

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
        $events = $this->getEverything($this->event);

        foreach ($events as $event) {
            $this->addEventDetails($event);
        }

        return $this->convertObjectToArray($events);
    }

    public function getEvent(ServerRequest $request, $args)
    {
        $id = $args['id'];
        $event = $this->event::find($id);
        $this->addEventDetails($event);
        return $this->convertObjectToArray($event);
    }

    /**
     * @param $event
     */
    protected function addEventDetails($event): void
    {
        if(isset($event)) {
            $event->homeTeam;
            $event->awayTeam;
            $event->location;
        }
    }

}