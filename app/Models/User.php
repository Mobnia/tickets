<?php declare(strict_types=1);

namespace App\Models;


/**
 * Class User
 *
 * @package \App\Models
 */
class User extends Base
{
    protected $table = 'person';

    const MORPH_NAME = 'user';
}