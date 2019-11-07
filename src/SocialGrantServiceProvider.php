<?php

namespace Adaojunior\PassportSocialGrant;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

class SocialGrantServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->resolving(AuthorizationServer::class, function (AuthorizationServer $server) {
            $grant = $this->makeGrant();
            $server->enableGrantType($grant, Passport::tokensExpireIn());
        });
    }

    protected function makeGrant()
    {
        $grant = new SocialGrant(
            $this->app->make(SocialGrantUserProvider::class),
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
