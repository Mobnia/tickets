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
}