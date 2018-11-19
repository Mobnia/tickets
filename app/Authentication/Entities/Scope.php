<?php declare(strict_types=1);

namespace App\Authentication\Entities;


use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\ScopeTrait;

/**
 * Class Scope
 *
 * @package \App\Authentication\Entities
 */
class Scope implements ScopeEntityInterface
{
    use EntityTrait, ScopeTrait;

    public static $scopes = [
        'read' => 'read',
        'write' => 'write'
    ];

    public function __construct($name)
    {
        $this->setIdentifier($name);
    }

    public static function hasScope($scope): bool
    {
        return $scope === '*' || array_key_exists($scope, static::$scopes);
    }
}