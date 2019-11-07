<?php

namespace Adaojunior\Passport;

use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

interface SocialGrantUserProvider
{
    /**
     * Retrieve a user by provider and access token.
     *
     * @param string $provider
     * @param string $accessToken
     * @param ClientEntityInterface $client
     * @return UserEntityInterface|null
     */
    public function getUserEntityByAccessToken(string $provider, string $accessToken, ClientEntityInterface $client):? UserEntityInterface;
}
