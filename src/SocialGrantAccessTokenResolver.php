<?php


namespace Adaojunior\Passport;


interface SocialGrantAccessTokenResolver
{
    /**
     * Resolves user by given network and access token.
     *
     * @param string $network
     * @param string $accessToken
     * @return mixed
     */
    public function resolve(string $network, string $accessToken);
}
