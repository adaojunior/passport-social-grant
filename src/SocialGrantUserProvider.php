<?php

namespace Adaojunior\Passport;

use League\OAuth2\Server\Entities\ClientEntityInterface;

interface SocialGrantUserProvider
{
    /**
     * Retrieve a user by provider and access token.
     *
     * @param string $provider
     * @param string $accessToken
     * @param ClientEntityInterface $client
     * @return mixed
     */
    public function retrieveByAccessToken(string $provider, string $accessToken, ClientEntityInterface $client);
}
