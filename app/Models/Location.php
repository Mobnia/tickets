<?php declare(strict_types=1);

namespace App\Models;


/**
 * Class Event
 *
 * @package \App\Models
 * @method static find($locationId)
 */
class Location extends Base
{
    protected $table = 'sport_location';

    const MORPH_NAME = 'location';

    public function events()
    {
        return $this->hasMany(Event::class, 'location_id');
    }
}