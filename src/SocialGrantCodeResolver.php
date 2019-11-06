<?php

namespace Adaojunior\Passport;

use League\OAuth2\Server\Entities\ClientEntityInterface;

interface SocialGrantCodeResolver
{
    /**
     * Resolves user by given network and code.
     *
     * @param string $network
     * @param string $code
     * @param ClientEntityInterface $client
     * @return mixed
     */
    public function resolve(string $network, string $code, ClientEntityInterface $client);
}
