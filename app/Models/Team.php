<?php declare(strict_types=1);

namespace App\Models;


/**
 * Class Event
 *
 * @package \App\Models
 */
class Team extends Base
{
    protected $table = 'sport_team';

    const MORPH_NAME = 'team';

    public function location()
    {
        return $this->belongsTo(Location::class, 'home_field_id');
    }
}