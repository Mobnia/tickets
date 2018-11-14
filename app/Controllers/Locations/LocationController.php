<?php declare(strict_types=1);

namespace App\Controllers\Locations;


use App\Controllers\BaseController;
use App\Models\Location;
use Zend\Diactoros\ServerRequest as Request;

/**
 * Class LocationController
 *
 * @package \App\Controllers\Events\Locations
 */
class LocationController extends BaseController
{
    private $locations;

    public function __construct(Request $request, Location $location)
    {
        $this->locations = $location;

        parent::__construct($request);
    }

    public function getLocations()
    {
        $locations = $this->getAllRecords($this->locations);

        foreach ($locations as $location) {
            $this->addLocationDetails($location);
        }

        return $this->convertObjectToArray($locations);
    }

    public function addLocationDetails($location)
    {
        $location->events;
    }
}