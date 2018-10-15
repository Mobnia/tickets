<?php declare(strict_types=1);

namespace App\Controllers\Events;


use App\Controllers\BaseController;
use App\Models\Event;
/**
 * Class EventController
 *
 * @package \App\Controllers\Events
 */
class EventController extends BaseController
{
    public function getUpcomingEvents()
    {
        $events = Event::all();

        foreach ($events as $event) {
            $event->homeTeam;
            $event->awayTeam;
            $event->location;
        }

        return [
            'data' => $this->convertObjectToArray($events),
        ];
    }

}