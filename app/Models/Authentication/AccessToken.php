<?php declare(strict_types=1);

namespace App\Models\Authentication;


use App\Models\Base;

/**
 * Class AccessToken
 *
 * @property string id
 * @property string user_id
 * @property string client_id
 * @property string scopes
 * @property bool is_revoked
 * @property \DateTime created_date_time
 * @property \DateTime updated_date_time
 * @property \DateTime expires_date_time
 * @package \App\Models\Authentication
 */
class AccessToken extends Base
{

    protected $table = 'access_tokens';

    const MORPH_NAME = 'access_token';
}