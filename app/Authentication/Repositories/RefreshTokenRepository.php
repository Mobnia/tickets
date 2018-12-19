<?php declare(strict_types=1);

namespace App\Authentication\Repositories;

use App\Authentication\Entities\RefreshToken;
use App\Models\Authentication\RefreshToken as AppRefreshToken;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * Class RefreshTokenRepository
 *
 * @package \App\Authentication\Entities
 */
class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    private $appRefreshToken;
    private $accessTokenRepository;

    public function __construct(AppRefreshToken $refreshToken, AccessTokenRepository $accessTokenRepository)
    {
        $this->appRefreshToken = $refreshToken;
        $this->accessTokenRepository = $accessTokenRepository;
    }

    /**
     * Creates a new refresh token
     *
     * @return RefreshTokenEntityInterface
     */
    public function getNewRefreshToken()
    {
        return new RefreshToken();
    }

    /**
     * Create a new refresh token_name.
     *
     * @param RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $refreshToken = new AppRefreshToken();

        $refreshToken->id = $refreshTokenEntity->getIdentifier();
        $refreshToken->access_token_id = $refreshTokenEntity->getAccessToken()->getIdentifier();
        $refreshToken->expiry_datetime = $refreshTokenEntity->getExpiryDateTime();

        if(!$refreshToken->save())
            throw UniqueTokenIdentifierConstraintViolationException::create();
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId
     */
    public function revokeRefreshToken($tokenId)
    {
        if ($this->appRefreshToken->find($tokenId) == null)
            return;

        $this->appRefreshToken->revoke();
        $this->appRefreshToken->save();
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $tokenPersistEntity = $this->appRefreshToken->find($tokenId);

        if ($tokenPersistEntity == null || $tokenPersistEntity->is_revoked)
            return true;

        return $this->accessTokenRepository->isAccessTokenRevoked($tokenPersistEntity->access_token_id);
    }
}