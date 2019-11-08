# Social Grant for Laravel Passport

This package adds a social grant to your Oauth2 Server.

## Installation

You can install the package via composer:

```
composer require adaojunior/passport-social-grant
```

The package will automatically register its service provider. Or you may manually add the service provider in your `config/app.php` file:

```php
'providers' => [
    // ...
    Adaojunior\PassportSocialGrant\SocialGrantServiceProvider::class,
];
```

## Setup

1. Implement the `SocialGrantUserProvider` interface:

```php
<?php

namespace App\SocialGrant;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Contracts\Auth\Authenticatable;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Adaojunior\PassportSocialGrant\SocialGrantUserProvider;

class UserProvider implements SocialGrantUserProvider
{
    /**
     * Retrieve a user by provider and access token.
     *
     * @param string $provider
     * @param string $accessToken
     * @param ClientEntityInterface $client
     * @return Authenticatable|null
     */
    public function getUserByAccessToken(string $provider, string $accessToken, ClientEntityInterface $client):? Authenticatable
    {

    }
}
```

2. Bind `SocialGrantUserProvider` interface to your implementation in the `register` method of your application service provider `app/Providers/AppServiceProvider.php`:

```php
$this->app->bind(
    Adaojunior\PassportSocialGrant\SocialGrantUserProvider::class,
    App\SocialGrant\UserProvider::class
);
```

## Usage


```php
$response = $http->post('http://your.app/oauth/token', [
    'form_params' => [
        'grant_type' => 'social',
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'provider' => $providerName, // name of provider (e.g., 'facebook', 'google' etc.)
        'access_token' => $providerAccessToken, // access token issued by specified provider
    ],
]);
```
