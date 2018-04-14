<?php

namespace Adaojunior\Passport;

use Closure;

interface SocialUserResolverInterface
{
    /**
     * Resolves user by given network and access token.
     *
     * @param string $network
     * @param string $accessToken
     * @param string|null $accessTokenSecret
     * @param Closure $getRequestParam
     * @return mixed
     */
    public function resolve($network, $accessToken, $accessTokenSecret = null, Closure $getRequestParam);
}
