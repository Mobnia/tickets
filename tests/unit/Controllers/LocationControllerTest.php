<?php declare(strict_types=1);

namespace App\Test\unit\Controllers;



use App\Controllers\Locations\LocationController;
use App\Models\Location;

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
        $this->locationController = new LocationController($this->request, $this->location);
    }

    public function testGetLocations()
    {
        $this->assertArrayHasKey('data', $this->locationController->getLocations());
    }
}