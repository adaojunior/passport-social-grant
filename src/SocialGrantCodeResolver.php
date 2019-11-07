<?php

namespace Adaojunior\Passport;

use League\OAuth2\Server\Entities\ClientEntityInterface;

interface SocialGrantCodeResolver
{
    /**
     * Resolves user by given provider and code.
     *
     * @param string $provider
     * @param string $code
     * @param ClientEntityInterface $client
     * @return mixed
     */
    public function resolve(string $provider, string $code, ClientEntityInterface $client);
}
