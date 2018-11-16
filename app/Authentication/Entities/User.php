<?php declare(strict_types=1);

namespace App\Authentication\Entities;


use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * Class User
 *
 * @package \App\Authentication\Entities
 */
class User implements UserEntityInterface
{
    use EntityTrait;


    public function __construct($identifier)
    {
        $this->setIdentifier($identifier);
    }
}