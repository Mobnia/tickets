<?php declare(strict_types=1);

namespace App\Controllers\Tickets;


use App\Controllers\BaseController;
use App\Models\Ticket;
use Aura\Filter\ValueFilter;
use League\Route\Http\Exception\BadRequestException;
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

    public function __construct(ValueFilter $filter, Ticket $ticket)
    {
        $this->tickets = $ticket;
        parent::__construct($filter);
    }

    public function getTicketsForEvent(Request $request, $args)
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

    private function validateRequest($buyerId): void
    {
        if (!$this->filter->validate($buyerId, 'int')) {
            throw new BadRequestException('The supplied buyer id is inaccurate');
        }
    }

    private function getRequestBody(ServerRequest $request)
    {
        return json_decode($request->getBody()->getContents());
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