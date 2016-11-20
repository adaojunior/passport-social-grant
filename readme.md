# Social Grant for Laravel Passport

## Installation

This package can be installed through Composer.

```
composer require adaojunior/passport-social-grant
```

You must install this service provider.

```php
// config/app.php
'providers' => [
    ...
    Adaojunior\Passport\SocialGrantServiceProvider::class,
    ...
];
```

You must also implement `Adaojunior\Passport\SocialUserResolverInterface`:

```php
...

use Adaojunior\Passport\SocialGrantException;
use Adaojunior\Passport\SocialUserResolverInterface;

class SocialUserResolver implements SocialUserResolverInterface
{

    /**
     * Resolves user by given network and access token.
     *
     * @param string $network
     * @param string $accessToken
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function resolve($network, $accessToken)
    {
        switch ($network) {
            case 'facebook':
                return $this->authWithFacebook($accessToken);
                break;
            default:
                throw SocialGrantException::invalidNetwork();
                break;
        }
    }
    
    
    /**
     * Resolves user by facebook access token.
     *
     * @param string $accessToken
     * @return \App\User
     */
    protected function authWithFacebook($accessToken)
    {
        ...
    }
}

```

Register on AppServiceProvider:

```php
$this->app->singleton(SocialUserResolverInterface::class, SocialUserResolver::class);
```
