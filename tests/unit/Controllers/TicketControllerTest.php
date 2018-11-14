<?php declare(strict_types=1);

namespace App\Test\unit\Controllers;


use App\Controllers\Tickets\TicketController;
use App\Models\Ticket;
use League\Route\Http\Exception\BadRequestException;
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
        $this->ticketController = new TicketController($this->filter, $this->ticket);
    }

    public function testGetTickets()
    {
        //$this->assertArrayHasKey('data', $this->ticketController->getTicketsForEvent($this->request, 1));

        $this->expectException(NotFoundException::class);
        $this->ticketController->getTicketsForEvent($this->request, 0);
    }

    public function testBuyTicket()
    {
        $body = [
            'buyerId' => 1,
        ];
        $request = $this->addBodyToRequest($body);
        $this->assertArrayHasKey('data', $this->ticketController->buyTicket($request, ['id' => 5589]));
    }

    public function testBuyTicketWithWrongID()
    {
        $body = [
            'buyerId' => 'abcd',
        ];
        $request = $this->addBodyToRequest($body);

        $this->expectException(BadRequestException::class);
        $this->ticketController->buyTicket($request, ['id' => 5589]);
    }

    private function addBodyToRequest($body)
    {
        $stream = (new StreamFactory())->createStream(json_encode($body));
        $request = $this->request->withBody($stream);
        return $request;
    }
}