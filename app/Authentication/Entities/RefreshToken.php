<?php declare(strict_types=1);

namespace App\Authentication\Entities;



use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

/**
 * Class AuthCode
 *
 * @package \App\Authentication\Entities
 */
class RefreshToken implements RefreshTokenEntityInterface
{
    use EntityTrait, RefreshTokenTrait;
}