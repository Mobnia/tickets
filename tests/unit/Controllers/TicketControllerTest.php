<?php declare(strict_types=1);

namespace App\Test\unit\Controllers;


use App\Controllers\Tickets\TicketController;
use App\Models\Ticket;
use League\Route\Http\Exception\NotFoundException;
use Zend\Diactoros\StreamFactory;

/**
 * Class TicketControllerTest
 *
 * @package \App\Test\unit\Controllers
 */
class TicketControllerTest extends BaseController
{
    private $ticket;
    private $ticketController;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->ticket = new Ticket();
        $this->ticketController = new TicketController($this->request, $this->ticket);
    }

    public function testGetTickets()
    {
        //$this->assertArrayHasKey('data', $this->ticketController->getTicketsForEvent($this->request, 1));

        $this->expectException(NotFoundException::class);
        $this->ticketController->getTicketsForEvent($this->request, 0);
    }

    public function testBuyTicket()
    {
        $responseBody = [
            'buyerId' => 1,
        ];
        $stream = (new StreamFactory())->createStream(json_encode($responseBody));
        $request = $this->request->withBody($stream);
        $this->assertArrayHasKey('data', $this->ticketController->buyTicket($request, ['id' => 5589]));
    }
}