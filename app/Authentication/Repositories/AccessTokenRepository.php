<?php declare(strict_types=1);

namespace App\Authentication\Repositories;


use App\Authentication\Entities\AccessToken;
use App\Models\Authentication\AccessToken as ApplicationAccessToken;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * Class AccessTokenRepository
 *
 * @package \App\Authentication\Repositories
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    private $applicationAccessToken;

    public function __construct(ApplicationAccessToken $accessToken)
    {
        $this->applicationAccessToken = $accessToken;
    }

    /**
     * Create a new access token
     *
     * @param ClientEntityInterface $clientEntity
     * @param ScopeEntityInterface[] $scopes
     * @param mixed $userIdentifier
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return new AccessToken((string) $userIdentifier, $scopes);
    }

    /**
     * Persists a new access token to permanent storage.
     *
     * @param AccessTokenEntityInterface $accessTokenEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $accessToken = new ApplicationAccessToken();
        $accessToken->identifier = $accessTokenEntity->getIdentifier();
        $accessToken->userIdentifier = $accessTokenEntity->getUserIdentifier();
        $accessToken->clientIdentifier = $accessTokenEntity->getClient()->getIdentifier();
        $accessToken->scopes = $this->scopesToArray($accessTokenEntity->getScopes());
        $accessToken->isRevoked = false;
        $accessToken->created_date_time = new \DateTime();
        $accessToken->updated_date_time = new \DateTime();
        $accessToken->expires_date_time = $accessTokenEntity->getExpiryDateTime();

        if(!$accessToken->save())
            throw UniqueTokenIdentifierConstraintViolationException::create();
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId
     */
    public function revokeAccessToken($tokenId)
    {
        $accessToken = $this->applicationAccessToken->find($tokenId);

        if (!$accessToken)
            return;

        $accessToken->isRevoked = true;
        $accessToken->save();
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $accessToken = $this->applicationAccessToken->find($tokenId);

        if (!$accessToken)
            return true;

        return $accessToken->isRevoked;
    }

    private function scopesToArray(array $scopes): array
    {
        return array_map(function (ScopeEntityInterface $scope) {
            return $scope->getIdentifier();
        }, $scopes);
    }
}