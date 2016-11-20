<?php

namespace Adaojunior\Passport;


interface SocialUserResolverInterface
{
    /**
     * Resolves user by given network and access token.
     *
     * @param string $network
     * @param string $accessToken
     * @return mixed
     */
    public function resolve($network, $accessToken);
}
