<?php declare(strict_types=1);

namespace App\Controllers\Tickets;


use App\Controllers\BaseController;
use App\Models\Ticket;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest as Request;
use Zend\Diactoros\ServerRequest;

/**
 * Class TicketController
 *
 * @package \App\Controllers\Tickets
 */
class TicketController extends BaseController
{
    private $tickets;

    public function __construct(Request $request, Response $response, Ticket $ticket)
    {
        $this->tickets = $ticket;
        parent::__construct($request, $response);
    }

    public function getTicketsForEvent(ServerRequest $request, $args)
    {
        $eventId = $args['id'];
        $tickets = $this->tickets::where('sporting_event_id', $eventId)->get();
        return $this->convertObjectToArray($tickets);
    }
}