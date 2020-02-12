<?php

namespace Adaojunior\PassportSocialGrant;

use Illuminate\Contracts\Auth\Authenticatable;
use League\OAuth2\Server\Entities\ClientEntityInterface;

interface SocialGrantUserProvider
{
    /**
     * Retrieve a user by provider and access token.
     *
     * @param string $provider
     * @param string $accessToken
     * @param ClientEntityInterface $client
     * @return Authenticatable|null
     */
    public function getUserByAccessToken(string $provider, string $accessToken, ClientEntityInterface $client): ?Authenticatable;
}
