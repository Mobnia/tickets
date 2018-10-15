<?php declare(strict_types=1);

namespace App\Models;


/**
 * Class Board
 *
 * @package \App\Models
 */
class Board extends Base
{
    protected $table = 'boards';

    const MORPH_NAME = 'board';
}