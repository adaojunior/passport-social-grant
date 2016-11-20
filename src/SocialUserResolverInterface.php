<?php

namespace Adaojunior\Passport;


interface SocialUserResolverInterface
{
    public function resolve(string $network, string $accessToken);
}
