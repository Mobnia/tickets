<?php declare(strict_types=1);

namespace App\Authentication\Repositories;


use App\Authentication\Entities\User;
use App\Models\User as ApplicationUser;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 * Class UserRepository
 *
 * @package \App\Authentication\Repositories
 */
class UserRepository implements UserRepositoryInterface
{
    private $users;

    public function __construct(ApplicationUser $users)
    {
        $this->users = $users;
    }

    /**
     * Get a user entity.
     *
     * @param string $username
     * @param string $password
     * @param string $grantType The grant type used
     * @param ClientEntityInterface $clientEntity
     *
     * @return UserEntityInterface
     */
    public function getUserEntityByUserCredentials($username,
                                                   $password,
                                                   $grantType,
                                                   ClientEntityInterface $clientEntity)
    {
        $user = $this->users->where('username', $username);

        if ($user->password == $password)
            return null;

        return new User($user->id);
    }
}