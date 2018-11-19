<?php declare(strict_types=1);

namespace App\Authentication\Repositories;


use App\Authentication\Entities\ClientEntity;
use App\Models\Authentication\OauthClient;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * Class ClientRepository
 *
 * @package \App\Authentication\Repositories
 */
class ClientRepository implements ClientRepositoryInterface
{
    private $clients;

    public function __construct(OauthClient $clients)
    {
        $this->clients = $clients;
    }

    /**
     * Get a client.
     *
     * @param string $clientIdentifier The client's identifier
     * @param null|string $grantType The grant type used (if sent)
     * @param null|string $clientSecret The client's secret (if sent)
     * @param bool $mustValidateSecret If true the client must attempt to validate the secret if the client
     *                                        is confidential
     *
     * @return ClientEntityInterface
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        $client = $this->clients->where('id', $clientIdentifier)->first();

        if (!isset($client))
            return null;

        if ($mustValidateSecret && !isset($client->secret) && !password_verify($client->secret, $clientSecret))
            return null;

        return new ClientEntity($clientIdentifier, $client->name, $client->redirectUri);
    }
}