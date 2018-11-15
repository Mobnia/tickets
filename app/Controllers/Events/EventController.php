<?php declare(strict_types=1);

namespace App\Controllers\Events;


use App\Controllers\BaseController;
use App\Models\Event;
use Aura\Filter\ValueFilter;
use Zend\Diactoros\ServerRequest as Request;

/**
 * Class EventController
 *
 * @package \App\Controllers\Events
 */
class EventController extends BaseController
{
    private $event;

    public function __construct(ValueFilter $filter, Event $event)
    {
        $this->event = $event;
        parent::__construct($filter);
    }

    public function getUpcomingEvents(Request $request)
    {
        $events = $this->getAllRecords($this->event);

        $page = $this->getPage($request);

        foreach ($events as $event) {
            $this->addEventDetails($event);
        }

        return $this->returnResponse($events, $page);
    }

    public function getEvent(Request $request, $args)
    {
        $eventId = $args['id'];
        $event = $this->event::find($eventId);

        if(isset($event)) $this->addEventDetails($event);

        return $this->returnResponse($event);
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