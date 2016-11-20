<?php

namespace LeagueTests\Grant;

use Adaojunior\Passport\SocialGrant;
use Adaojunior\Passport\SocialUserResolverInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

use LeagueTests\Stubs\AccessTokenEntity;
use LeagueTests\Stubs\ClientEntity;
use LeagueTests\Stubs\RefreshTokenEntity;
use LeagueTests\Stubs\StubResponseType;
use LeagueTests\Stubs\UserEntity;
use Zend\Diactoros\ServerRequest;

class PasswordGrantTest extends \PHPUnit_Framework_TestCase
{
    public function testGetIdentifier()
    {
        $resolverMock = $this->getMockBuilder(SocialUserResolverInterface::class)->getMock();
        $refreshTokenRepositoryMock = $this->getMockBuilder(RefreshTokenRepositoryInterface::class)->getMock();

        $grant = new SocialGrant($resolverMock, $refreshTokenRepositoryMock);
        $this->assertEquals('social', $grant->getIdentifier());
    }

    public function testRespondToRequest()
    {
        $client = new ClientEntity();
        $clientRepositoryMock = $this->getMockBuilder(ClientRepositoryInterface::class)->getMock();
        $clientRepositoryMock->method('getClientEntity')->willReturn($client);

        $accessTokenRepositoryMock = $this->getMockBuilder(AccessTokenRepositoryInterface::class)->getMock();
        $accessTokenRepositoryMock->method('getNewToken')->willReturn(new AccessTokenEntity());
        $accessTokenRepositoryMock->method('persistNewAccessToken')->willReturnSelf();

        $resolverMock = $this->getMockBuilder(SocialUserResolverInterface::class)->getMock();
        $userEntity = new UserEntity();
        $resolverMock->method('resolve')->willReturn($userEntity);

        $refreshTokenRepositoryMock = $this->getMockBuilder(RefreshTokenRepositoryInterface::class)->getMock();
        $refreshTokenRepositoryMock->method('persistNewRefreshToken')->willReturnSelf();
        $refreshTokenRepositoryMock->method('getNewRefreshToken')->willReturn(new RefreshTokenEntity());

        $scopeRepositoryMock = $this->getMockBuilder(ScopeRepositoryInterface::class)->getMock();
        $scopeRepositoryMock->method('finalizeScopes')->willReturnArgument(0);

        $grant = new SocialGrant($resolverMock, $refreshTokenRepositoryMock);
        $grant->setClientRepository($clientRepositoryMock);
        $grant->setAccessTokenRepository($accessTokenRepositoryMock);
        $grant->setScopeRepository($scopeRepositoryMock);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody(
            [
                'client_id'     => 'foo',
                'client_secret' => 'bar',
                'network'      => 'foo',
                'access_token' => 'bar',
            ]
        );

        $responseType = new StubResponseType();
        $grant->respondToAccessTokenRequest($serverRequest, $responseType, new \DateInterval('PT5M'));

        $this->assertTrue($responseType->getAccessToken() instanceof AccessTokenEntityInterface);
        $this->assertTrue($responseType->getRefreshToken() instanceof RefreshTokenEntityInterface);
    }

    /**
     * @expectedException \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function testRespondToRequestMissingNetwork()
    {
        $client = new ClientEntity();
        $clientRepositoryMock = $this->getMockBuilder(ClientRepositoryInterface::class)->getMock();
        $clientRepositoryMock->method('getClientEntity')->willReturn($client);
        $accessTokenRepositoryMock = $this->getMockBuilder(AccessTokenRepositoryInterface::class)->getMock();
        $resolverMock = $this->getMockBuilder(SocialUserResolverInterface::class)->getMock();
        $refreshTokenRepositoryMock = $this->getMockBuilder(RefreshTokenRepositoryInterface::class)->getMock();
        $grant = new SocialGrant($resolverMock, $refreshTokenRepositoryMock);
        $grant->setClientRepository($clientRepositoryMock);
        $grant->setAccessTokenRepository($accessTokenRepositoryMock);
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody(
            [
                'client_id'     => 'foo',
                'client_secret' => 'bar',
            ]
        );
        $responseType = new StubResponseType();
        $grant->respondToAccessTokenRequest($serverRequest, $responseType, new \DateInterval('PT5M'));
    }

    /**
     * @expectedException \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function testRespondToRequestMissingAccessToken()
    {
        $client = new ClientEntity();
        $clientRepositoryMock = $this->getMockBuilder(ClientRepositoryInterface::class)->getMock();
        $clientRepositoryMock->method('getClientEntity')->willReturn($client);
        $accessTokenRepositoryMock = $this->getMockBuilder(AccessTokenRepositoryInterface::class)->getMock();
        $resolverMock = $this->getMockBuilder(SocialUserResolverInterface::class)->getMock();
        $refreshTokenRepositoryMock = $this->getMockBuilder(RefreshTokenRepositoryInterface::class)->getMock();
        $grant = new SocialGrant($resolverMock, $refreshTokenRepositoryMock);
        $grant->setClientRepository($clientRepositoryMock);
        $grant->setAccessTokenRepository($accessTokenRepositoryMock);
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody(
            [
                'client_id'     => 'foo',
                'client_secret' => 'bar',
                'network'      => 'facebook',
            ]
        );
        $responseType = new StubResponseType();
        $grant->respondToAccessTokenRequest($serverRequest, $responseType, new \DateInterval('PT5M'));
    }

    /**
     * @expectedException \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function testRespondToRequestBadCredentials()
    {
        $client = new ClientEntity();
        $clientRepositoryMock = $this->getMockBuilder(ClientRepositoryInterface::class)->getMock();
        $clientRepositoryMock->method('getClientEntity')->willReturn($client);
        $accessTokenRepositoryMock = $this->getMockBuilder(AccessTokenRepositoryInterface::class)->getMock();
        $resolverMock = $this->getMockBuilder(SocialUserResolverInterface::class)->getMock();
        $resolverMock->method('resolve')->willReturn(null);
        $refreshTokenRepositoryMock = $this->getMockBuilder(RefreshTokenRepositoryInterface::class)->getMock();
        $grant = new SocialGrant($resolverMock, $refreshTokenRepositoryMock);
        $grant->setClientRepository($clientRepositoryMock);
        $grant->setAccessTokenRepository($accessTokenRepositoryMock);
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody(
            [
                'client_id'     => 'foo',
                'client_secret' => 'bar',
                'network'      => 'facebook',
                'access_token'      => 'ACCESS_TOKEN',
            ]
        );
        $responseType = new StubResponseType();
        $grant->respondToAccessTokenRequest($serverRequest, $responseType, new \DateInterval('PT5M'));
    }
}
