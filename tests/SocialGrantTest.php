<?php


namespace Adaojunior\SocialGrant\Tests;

use PHPUnit\Framework\TestCase;
use Adaojunior\Passport\SocialGrant;
use Adaojunior\Passport\SocialGrantUserResolver;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class SocialGrantTest extends TestCase
{
    public function testGetIdentifier()
    {
        $grant = new SocialGrant(
            $this->getMockBuilder(SocialGrantUserResolver::class)->getMock(),
            $this->getMockBuilder(RefreshTokenRepositoryInterface::class)->getMock()
        );

        $this->assertEquals('social', $grant->getIdentifier());
    }
}
