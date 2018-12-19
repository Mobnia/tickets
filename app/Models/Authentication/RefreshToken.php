<?php declare(strict_types=1);

namespace App\Models\Authentication;


use App\Models\Base;

/**
 * Class RefreshToken
 *
 * @property bool is_revoked
 * @property string id
 * @property string access_token_id
 * @property \DateTime expiry_datetime
 * @package \App\Models\Authentication
 */
class RefreshToken extends Base
{
    protected $table = 'refresh_tokens';

    const MORPH_NAME = 'refresh_token';

    public function revoke()
    {
        $this->is_revoked = true;
    }
}