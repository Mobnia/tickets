<?php declare(strict_types=1);

namespace App\Controllers\Tickets;


use App\Controllers\BaseController;
use App\Models\Ticket;
use Aura\Filter\ValueFilter;
use Zend\Diactoros\ServerRequest as Request;

/**
 * Class TicketController
 *
 * @package \App\Controllers\Tickets
 */
class TicketController extends BaseController
{
    private $tickets;

    public function __construct(ValueFilter $filter, Ticket $ticket)
    {
        $this->tickets = $ticket;
        parent::__construct($filter);
    }

    public function getTicketsForEvent(Request $request, $args): array
    {
        $eventId = $args['id'];
        $page = $this->getPage($request);

        $tickets = $this->tickets->where('sporting_event_id', $eventId)->where('ticketholder_id', null)->get();

        return $this->returnResponse($tickets, $page);
    }
}