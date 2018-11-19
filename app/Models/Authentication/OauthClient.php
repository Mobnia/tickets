<?php declare(strict_types=1);

namespace App\Models\Authentication;


use App\Models\Base;

/**
 * Class OauthClient
 *
 * @package \App\Models\Authentication
 */
class OauthClient extends Base
{
    protected $table = 'oauth_clients';

    const MORPH_NAME = 'oauthClient';

    public function accessTokens()
    {
        return $this->hasMany(AccessToken::class, 'client_id');
    }

}