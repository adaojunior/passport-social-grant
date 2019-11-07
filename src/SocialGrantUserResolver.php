<?php

namespace Adaojunior\Passport;

use League\OAuth2\Server\Entities\ClientEntityInterface;

interface SocialGrantUserResolver
{
    /**
     * Resolves user by given provider and access token.
     *
     * @param string $provider
     * @param string $accessToken
     * @param ClientEntityInterface $client
     * @return mixed
     */
    public function resolve(string $provider, string $accessToken, ClientEntityInterface $client);
}
