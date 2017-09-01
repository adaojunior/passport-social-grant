# Social Grant for Laravel Passport

This package is useful to combine your Oauth2 Server with Social Login (facebook, google, github ...).

If you have a api that accepts registration/login using google, facebook, github or any other social login,
you will be able to exchange the access token given by the social login provider to a `access_token` + `refresh_token` from our own application.
You will be able to resolve a existing user or create a new user if a user is not yet registered on your application.

## Installation

This package can be installed through Composer.

```
composer require adaojunior/passport-social-grant
```

In Laravel 5.5 the service provider will automatically get registered. In older versions of the framework just add the service provider in config/app.php file:


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
    public function resolve($network, $accessToken, $accessTokenSecret = null)
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


## Request

```php
$response = $http->post('http://your-app.com/oauth/token', [
    'form_params' => [
        'grant_type' => 'social',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'network' => 'facebook', /// or any other network that your server is able to resolve.
        'access_token' => 'A_ACCESS_TOKEN_PROVIDED_BY_THE_SOCIAL_LOGIN_PROVIDER',
    ],
]);
```
