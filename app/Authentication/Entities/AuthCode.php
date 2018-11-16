<?php declare(strict_types=1);

namespace App\Authentication\Entities;


use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * Class AuthCode
 *
 * @package \App\Authentication\Entities
 */
class AuthCode implements AuthCodeEntityInterface
{
    use EntityTrait, AuthCodeTrait, TokenEntityTrait;

}