<?php declare(strict_types=1);

namespace App\Providers;


use App\Authentication\Repositories\RefreshTokenRepository;
use App\Authentication\Repositories\AccessTokenRepository;
use App\Authentication\Repositories\ClientRepository;
use App\Authentication\Repositories\ScopeRepository;
use App\Authentication\Repositories\UserRepository;
use App\Models\Authentication\AccessToken;
use App\Models\Authentication\OauthClient;
use App\Models\Authentication\RefreshToken;
use App\Models\User;
use DateInterval;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;

/**
 * Class AuthenticationProvider
 *
 * @package \App\Providers
 */
class AuthenticationProvider extends AbstractServiceProvider
{

    public function register()
    {
        list(
            $clientRepository, $scopeRepository,
            $userRepository, $accessTokenRepository,
            $refreshTokenRepository
            ) = $this->initRepositories();

        $privateKey = __DIR__ . '/../../config/oauth/private.key';

        $encryptionKey = '3TvjQrfNPWySOh3ss8kbU7M7yPgCxBS3yRbvYdQn9ko=';

        $this->container->singleton(
            AuthorizationServer::class,
            function () use ($clientRepository, $scopeRepository,
                $userRepository, $accessTokenRepository,
                $refreshTokenRepository, $privateKey, $encryptionKey)
            {
                $server = new AuthorizationServer(
                    $clientRepository,
                    $accessTokenRepository,
                    $scopeRepository,
                    $privateKey,
                    $encryptionKey
                );

                $passwordGrant = new PasswordGrant($userRepository, $refreshTokenRepository);
                $refreshGrant = new RefreshTokenGrant($refreshTokenRepository);
                $passwordGrant->setRefreshTokenTTL(new DateInterval('P1M'));
                $refreshGrant->setRefreshTokenTTL(new DateInterval('P1M'));

                $server->enableGrantType($passwordGrant, new DateInterval('PT1H'));
                $server->enableGrantType($refreshGrant, new DateInterval('PT1H'));

                return $server;
            }
        );
    }

    private function initRepositories(): array
    {
        // TODO: fix this architecture
        $clients = $this->container->make(OauthClient::class);
        $users = $this->container->make(User::class);
        $accessTokens = $this->container->make(AccessToken::class);
        $refreshTokens = $this->container->make(RefreshToken::class);

        $clientRepository = new ClientRepository($clients);
        $scopeRepository = new ScopeRepository();
        $userRepository = new UserRepository($users);
        $accessTokenRepository = new AccessTokenRepository($accessTokens);
        $refreshTokenRepository = new RefreshTokenRepository($refreshTokens, $accessTokenRepository);
        return [$clientRepository, $scopeRepository, $userRepository, $accessTokenRepository, $refreshTokenRepository];
    }

}