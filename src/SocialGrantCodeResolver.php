<?php

namespace Adaojunior\Passport;

interface SocialGrantCodeResolver
{
    /**
     * Resolves user by given network and code.
     *
     * @param string $network
     * @param string $code
     * @return mixed
     */
    public function resolve(string $network, string $code);
}
