<?php declare(strict_types=1);

namespace App\Authentication\Entities;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

/**
 * Class ClientEntity
 *
 * @package \App\Authentication\Entities
 */
class ClientEntity implements ClientEntityInterface
{
    use ClientTrait, EntityTrait;

    public function __construct(int $identifier, string $name, string $redirectUri)
    {
        $this->setIdentifier($identifier);
        $this->name = $name;
        $this->redirectUri = explode(',', $redirectUri);
    }
}