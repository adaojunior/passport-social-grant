<?php

namespace Adaojunior\PassportSocialGrant;

use League\OAuth2\Server\Exception\OAuthServerException;

class SocialGrantException extends OAuthServerException
{
    public static function invalidProvider()
    {
        return self::invalidRequest('provider', 'Invalid provider');
    }

    public static function invalidAccessToken()
    {
        return self::invalidRequest('access_token', 'Invalid access token');
    }
}
