<?php

namespace Adaojunior\Passport;

use League\OAuth2\Server\Entities\ClientEntityInterface;

interface SocialGrantAccessTokenResolver
{
    /**
     * Resolves user by given network and access token.
     *
     * @param string $network
     * @param string $accessToken
     * @param ClientEntityInterface $client
     * @return mixed
     */
    public function resolve(string $network, string $accessToken, ClientEntityInterface $client);
}
