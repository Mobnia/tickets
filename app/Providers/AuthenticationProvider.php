<?php declare(strict_types=1);

namespace App\Providers;


use App\Authentication\Repositories\AccessTokenRepository;
use App\Authentication\Repositories\ClientRepository;
use App\Authentication\Repositories\ScopeRepository;
use App\Models\Authentication\AccessToken;
use App\Models\Authentication\OauthClient;
use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\ImplicitGrant;

/**
 * Class AuthenticationProvider
 *
 * @package \App\Providers
 */
class AuthenticationProvider extends AbstractServiceProvider
{

    public function register()
    {
        list($clientRepository, $scopeRepository, $tokenRepository) = $this->initRepositories();

        $privateKey = __DIR__ . '/../../config/oauth/private.key';

        $encryptionKey = '3TvjQrfNPWySOh3ss8kbU7M7yPgCxBS3yRbvYdQn9ko=';

        $this->container->singleton(
            AuthorizationServer::class,
            function () use ($clientRepository, $scopeRepository, $tokenRepository, $privateKey, $encryptionKey)
            {
                $server = new AuthorizationServer(
                    $clientRepository,
                    $tokenRepository,
                    $scopeRepository,
                    $privateKey,
                    $encryptionKey
                );

                $server->enableGrantType(
                    new ImplicitGrant(new DateInterval('PT1H')),
                    new DateInterval('PT1H')
                );

                return $server;
            }
        );
    }

    private function initRepositories(): array
    {
        // TODO: fix this architecture
        $clients = $this->container->make(OauthClient::class);
        $accessTokens = $this->container->make(AccessToken::class);

        $clientRepository = new ClientRepository($clients);
        $scopeRepository = new ScopeRepository();
        $tokenRepository = new AccessTokenRepository($accessTokens);
        return [$clientRepository, $scopeRepository, $tokenRepository];
    }

}