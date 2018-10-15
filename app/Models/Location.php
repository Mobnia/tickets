<?php declare(strict_types=1);

namespace App\Models;


/**
 * Class Event
 *
 * @package \App\Models
 */
class Location extends Base
{
    protected $table = 'sport_location';

    const MORPH_NAME = 'location';
}