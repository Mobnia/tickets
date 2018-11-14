<?php declare(strict_types=1);

namespace App\Models;


/**
 * Class Ticket
 *
 * @package \App\Models
 */
class Ticket extends Base
{
    protected $table = 'sporting_event_ticket';

    const MORPH_NAME = 'ticket';
}