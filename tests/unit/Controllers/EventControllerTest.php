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
use Illuminate\Database\Capsule\Manager;
use PHPUnit\Framework\TestCase;

class EventControllerTest extends TestCase
{
    private $request;
    private $response;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        // TODO: Stop mocking what you don't own
        $this->event = new Event();
        $this->request = $this->createMock('Zend\Diactoros\ServerRequest');
        $this->response = $this->createMock('Zend\Diactoros\Response');

        $this->eventController = new EventController($this->request, $this->response, $this->event);

        parent::__construct($name, $data, $dataName);
    }

    protected function setUp()
    {
        $this->configureDatabase();
    }

    private function configureDatabase()
    {
        $dbUser = getenv("DB_USER");
        $dbHost = getenv("DB_HOST");
        $dbName = getenv("DB_NAME");
        $dbPassword = getenv("DB_PASSWORD");

        $db = new Manager();
        $db->addConnection([
            'driver' => "mysql",
            'host' => $dbHost,
            'database' => $dbName,
            'username' => $dbUser,
            'password' => $dbPassword,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $db->bootEloquent();
        $db->setAsGlobal();
    }

    public function testGetUpcomingEvents()
    {
        $this->assertArrayHasKey('data', $this->eventController->getUpcomingEvents());
    }

    public function testGetAnEvent()
    {
        $this->assertArrayHasKey('data', $this->eventController->getEvent(1));
    }
}
