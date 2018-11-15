<?php declare(strict_types=1);

namespace App\Test\unit\Controllers;



use App\Controllers\Locations\LocationController;
use App\Models\Location;
use League\Route\Http\Exception\NotFoundException;

/**
 * Class LocationControllerTest
 *
 * @package \App\Test\unit\Controllers
 */
class LocationControllerTest extends BaseController
{
    private $location;
    private $locationController;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->location = new Location();
        $this->locationController = new LocationController($this->filter, $this->location);
    }

    public function testGetLocations()
    {
        $this->assertArrayHasKey('data', $this->locationController->getLocations($this->request));
    }

    public function testGetLocation()
    {
        $this->assertArrayHasKey('data', $this->locationController->getLocation($this->request, ['id' => 1]));

        $this->expectException(NotFoundException::class);
        $this->assertArrayHasKey('data', $this->locationController->getLocation($this->request, ['id' => 0]));
    }
}