<?php declare(strict_types=1);

namespace App\Authentication\Repositories;


use App\Authentication\Entities\Scope;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * Class ScopeRepository
 *
 * @package \App\Authentication\Repositories
 */
class ScopeRepository implements ScopeRepositoryInterface
{

    /**
     * Return information about a scope.
     *
     * @param string $identifier The scope identifier
     *
     * @return ScopeEntityInterface
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        return Scope::hasScope($identifier)? new Scope($identifier) : null;
    }

    /**
     * Given a client, grant type and optional user identifier validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @param ScopeEntityInterface[] $scopes
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @param null|string $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(array $scopes,
                                   $grantType,
                                   ClientEntityInterface $clientEntity,
                                   $userIdentifier = null)
    {
        $validScopes = [];

        foreach ($scopes as $scope) {
            if (Scope::hasScope($scope->getIdentifier()))
                $validScopes[] = $scope;
        }

        return $validScopes;
    }
}