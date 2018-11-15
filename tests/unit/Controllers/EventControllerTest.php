<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: ifenna
 * Date: 11/9/18
 * Time: 12:32 PM
 */

namespace App\Test\unit\Controllers;

use App\Controllers\Events\EventController;
use App\Models\Event;

class EventControllerTest extends BaseController
{
    private $event;
    private $eventController;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName); // request and response are constructed in the parent

        $this->event = new Event();
        $this->eventController = new EventController($this->filter, $this->event);
    }

    public function testGetUpcomingEvents()
    {
        $this->assertArrayHasKey('data', $this->eventController->getUpcomingEvents($this->request));
    }

    public function testGetAnEvent()
    {
        $this->assertArrayHasKey('data', $this->eventController->getEvent($this->request, ['id' => 1]));
    }
}
