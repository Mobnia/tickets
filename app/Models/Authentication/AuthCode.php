<?php declare(strict_types=1);

namespace App\Models\Authentication;


use App\Models\Base;

/**
 * Class AuthCode
 *
 * @property string id
 * @property string client_id
 * @property int|null|string user_id
 * @property null|string redirect_uri
 * @property string scopes
 * @property bool is_revoked
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