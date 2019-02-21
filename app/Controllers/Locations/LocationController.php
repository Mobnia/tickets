<?php declare(strict_types=1);

namespace App\Controllers\Locations;


use App\Controllers\BaseController;
use App\Models\Location;
use Aura\Filter\ValueFilter;
use Zend\Diactoros\ServerRequest as Request;

/**
 * Class LocationController
 *
 * @package \App\Controllers\Events\Locations
 */
class LocationController extends BaseController
{
    private $locations;

    public function __construct(ValueFilter $filter, Location $location)
    {
        $this->locations = $location;

        parent::__construct($filter);
    }

    public function getLocations(Request $request)
    {
        $locations = $this->getAllRecords($this->locations);

        $page = $this->getPage($request);

        foreach ($locations as $location) {
            $this->addLocationDetails($location);
        }

        return $this->returnResponse($locations, $page);
    }

    public function getLocation(Request $request, $args): array
    {
        $locationId = $args['id'];
        $location = $this->locations::find($locationId);

        if(isset($location)) $this->addLocationDetails($location);

        return $this->returnResponse($location);
    }

    public function addLocationDetails($location): void
    {
        $location->events;
    }
}