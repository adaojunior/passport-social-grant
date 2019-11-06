<?php

namespace Adaojunior\Passport;

use DateInterval;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Bridge\User as UserEntity;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

class SocialGrant extends AbstractGrant
{
    const CODE = 'code';

    const ACCESS_TOKEN = 'access_token';

    public function __construct(RefreshTokenRepositoryInterface $refreshTokenRepository)
    {
        $this->setRefreshTokenRepository($refreshTokenRepository);
        $this->refreshTokenTTL = new DateInterval('P1M');
    }

    public function getIdentifier()
    {
        return 'social';
    }

    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ) {

        // Validate request
        $client = $this->validateClient($request);

        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));

        $user = $this->validateUser($request);

        // Finalize the requested scopes
        $scopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $scopes);

        $refreshToken = $this->issueRefreshToken($accessToken);

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    /**
     * @param ServerRequestInterface $request
     * @return UserEntityInterface
     * @throws OAuthServerException
     */
    protected function validateUser(ServerRequestInterface $request)
    {
        $type = $this->getType($request);

        $user = $this->getResolver($type)->resolve(
            $this->getParameter('network', $request),
            $this->getParameter($type, $request)
        );

        if ($user instanceof Authenticatable) {
            $user = new UserEntity($user->getAuthIdentifier());
        }

        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    /**
     * @param string $type
     * @return SocialGrantCodeResolver|SocialGrantAccessTokenResolver
     */
    protected function getResolver(string $type)
    {
        $class = config("social-grant.$type");

        $resolver = app($class);

        if ($type === self::CODE && $resolver instanceof SocialGrantCodeResolver) {
            return $resolver;
        } elseif ($type === self::ACCESS_TOKEN && $resolver instanceof SocialGrantAccessTokenResolver) {
            return $resolver;
        }

        throw SocialGrantException::serverError("Missing implementation of $type");
    }

    protected function getType(ServerRequestInterface $request)
    {
        $type = $this->getParameter('type', $request);

        if ($this->isValidType($type) && $this->isTypeEnabled($type)) {
            return $type;
        }

        throw SocialGrantException::invalidType($type);
    }

    protected function isTypeEnabled(string $type)
    {
        return ! is_null(config("social-grant.$type"));
    }

    public function isValidType($type)
    {
        return in_array($type, [self::CODE, self::ACCESS_TOKEN]);
    }

    protected function getParameter($param, ServerRequestInterface $request, $required = true)
    {
        $value = $this->getRequestParameter($param, $request);

        if (is_null($value) && $required) {
            throw OAuthServerException::invalidRequest($param);
        }

        return $value;
    }
}
