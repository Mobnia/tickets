<?php declare(strict_types=1);

namespace App\Models\Authentication;


use App\Models\Base;

/**
 * Class AccessToken
 *
 * @property string identifier
 * @property string userIdentifier
 * @property string clientIdentifier
 * @property array scopes
 * @property bool isRevoked
 * @property \DateTime created_date_time
 * @property \DateTime updated_date_time
 * @property \DateTime expires_date_time
 * @package \App\Models\Authentication
 */
class AccessToken extends Base
{
//    private $id;
//    private $userId;
//    private $clientId;
//    private $scopes;
//    private $revoked;
//    private $createdAt;
//    private $updatedAt;
//    private $expiresAt;

    protected $table = 'access_tokens';

    const MORPH_NAME = 'access_token';
}