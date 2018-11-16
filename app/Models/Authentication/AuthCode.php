<?php declare(strict_types=1);

namespace App\Models\Authentication;


use App\Models\Base;

/**
 * Class AuthCode
 *
 * @property string identifier
 * @property string clientIdentifier
 * @property int|null|string userIdentifier
 * @property null|string redirectUri
 * @property array|mixed scopes
 * @property bool isRevoked
 * @property \DateTime created_date_time
 * @property \DateTime updated_date_time
 * @property \DateTime expires_date_time
 * @package \App\Models\Authentication
 */
class AuthCode extends Base
{

    protected $table = 'auth_codes';

    const MORPH_NAME = 'auth_codes';
}