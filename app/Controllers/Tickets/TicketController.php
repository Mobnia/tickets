<?php declare(strict_types=1);

namespace App\Controllers\Tickets;


use App\Controllers\BaseController;
use App\Models\Ticket;
use Aura\Filter\ValueFilter;
use League\Route\Http\Exception\BadRequestException;
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

    public function getTicketsForEvent(Request $request, $args)
    {
        $eventId = $args['id'];
        $tickets = $this->tickets->where('sporting_event_id', $eventId)->where('ticketholder_id', null)->get();
        return $this->returnResponse($tickets);
    }

    public function buyTicket(Request $request, $args)
    {
        $buyer = $this->getBuyer($request);
        $ticketId = $args['id'];

        $this->verifyBuyer($buyer);

        $ticket = $this->processPurchase($ticketId, $buyer);

        return $this->returnResponse($ticket);
    }

    private function getBuyer(Request $request)
    {
        $requestBody = $this->getRequestBody($request);
        $buyer = $requestBody->buyerId;
        return $buyer;
    }

    private function getRequestBody(Request $request)
    {
        $requestBody = json_decode($request->getBody()->getContents());
        $this->ensureRequestDataExists($requestBody);
        $this->validateRequest($requestBody->buyerId, 'int');
        return $requestBody;
    }

    private function validateRequest($buyerId, $rule): void
    {
        if (!$this->filter->validate($buyerId, $rule)) {
            throw new BadRequestException('The supplied request is invalid');
        }
    }

    private function ensureRequestDataExists($body)
    {
        if (!isset($body->buyerId))
            throw new BadRequestException('The supplied request is invalid');
    }

    private function verifyBuyer($buyer)
    {
        return null;
    }

    private function processPurchase($ticketId, $buyer)
    {
        $ticket = $this->tickets::find($ticketId);
        $ticket->ticketholder_id = $buyer;
        $ticket->save();

        return $ticket;
    }
}