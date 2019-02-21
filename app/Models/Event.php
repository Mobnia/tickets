<?php declare(strict_types=1);

namespace App\Models;


/**
 * Class Event
 *
 * @package \App\Models
 * @method static find($eventId)
 */
class Event extends Base
{
    protected $table = 'sporting_event';

    const MORPH_NAME = 'event';

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}