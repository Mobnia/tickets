<?php declare(strict_types=1);

namespace App\Authentication\Repositories;


use App\Authentication\Entities\AuthCode;
use App\Models\Authentication\AuthCode as ApplicationAuthCode;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

/**
 * Class AuthCodeRepository
 *
 * @package \App\Authentication\Repositories
 */
class AuthCodeRepository implements AuthCodeRepositoryInterface
{

    private $applicationAuthCode;

    public function __construct(ApplicationAuthCode $authCode)
    {
        $this->applicationAuthCode = $authCode;
    }

    /**
     * Creates a new AuthCode
     *
     * @return AuthCodeEntityInterface
     */
    public function getNewAuthCode()
    {
        return new AuthCode();
    }

    /**
     * Persists a new auth code to permanent storage.
     *
     * @param AuthCodeEntityInterface $authCodeEntity
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $authCode = new ApplicationAuthCode();

        $authCode->identifier = $authCodeEntity->getIdentifier();
        $authCode->userIdentifier = $authCodeEntity->getUserIdentifier();
        $authCode->clientIdentifier = $authCodeEntity->getClient()->getIdentifier();
        $authCode->scopes = $this->scopesToArray($authCodeEntity->getScopes());
        $authCode->redirectUri = $authCodeEntity->getRedirectUri();
        $authCode->isRevoked = false;
        $authCode->created_date_time = new \DateTime();
        $authCode->updated_date_time = new \DateTime();
        $authCode->expires_date_time = $authCodeEntity->getExpiryDateTime();

        if (!$authCode->save())
            throw UniqueTokenIdentifierConstraintViolationException::create();
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId
     */
    public function revokeAuthCode($codeId)
    {
        $authCode = $this->applicationAuthCode->find($codeId);

        if (!$authCode)
            return;

        $authCode->isRevoked = true;
        $authCode->save();
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId)
    {
        $authCode = $this->applicationAuthCode->find($codeId);

        if (!$authCode)
            return true;

        return $authCode->isRevoked;
    }

    private function scopesToArray(array $scopes): array
    {
        return array_map(function (ScopeEntityInterface $scope) {
            return $scope->getIdentifier();
        }, $scopes);
    }
}