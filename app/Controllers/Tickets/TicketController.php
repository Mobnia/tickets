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

    public function __construct(Request $request, Ticket $ticket)
    {
        $this->tickets = $ticket;
        parent::__construct($request);
    }

    public function getTicketsForEvent(ServerRequest $request, $args)
    {
        $eventId = $args['id'];
        $tickets = $this->tickets::where('sporting_event_id', $eventId)->where('ticketholder_id', null)->get();
        return $this->convertObjectToArray($tickets);
    }

    public function buyTicket(ServerRequest $request, $args)
    {
        $requestBody = $this->getRequestBody($request);
        $buyer = $requestBody->buyerId;
        $ticketId = $args['id'];

        $this->verifyBuyer($buyer);

        $ticket = $this->processPurchase($ticketId, $buyer);

        return $this->convertObjectToArray($ticket);
    }

    private function getRequestBody(ServerRequest $request)
    {
        $requestBody = json_decode($request->getBody()->getContents());
        return $requestBody;
    }

    private function verifyBuyer($buyer)
    {
        return null;
    }

    private function processPurchase($ticketId, $buyer)
    {
        $ticket = $this->tickets::find($ticketId);
        $ticket->ticketholder_id = $buyer;
        $ticket->save;

        return $ticket;
    }
}