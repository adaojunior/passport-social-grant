<?php

namespace Adaojunior\Passport;

use League\OAuth2\Server\Exception\OAuthServerException;

class SocialGrantException extends OAuthServerException
{
    public static function invalidNetwork()
    {
        return self::invalidRequest('network', 'Invalid network');
    }

    public static function invalidAccessToken()
    {
        return self::invalidRequest('access_token', 'Invalid access token');
    }

    public static function invalidType(string $type)
    {
        return self::invalidRequest('type', "Type '$type' is not allowed");
    }
}
