<?php declare(strict_types=1);

namespace App\Authentication\Entities;


use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

/**
 * Class AuthCode
 *
 * @package \App\Authentication\Entities
 */
class AccessToken implements AccessTokenEntityInterface
{
    use EntityTrait, AccessTokenTrait, TokenEntityTrait;


    public function __construct(string $userIdentifier, array $scopes = [])
    {
        $this->setUserIdentifier($userIdentifier);

        foreach ($scopes as $scope)
            $this->addScope($scope);
    }
}