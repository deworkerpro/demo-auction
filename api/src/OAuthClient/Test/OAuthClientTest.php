<?php

declare(strict_types=1);

namespace App\OAuthClient\Test;

use App\OAuthClient\Identity;
use App\OAuthClient\OAuthClient;
use App\OAuthClient\Provider\Provider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class OAuthClientTest extends TestCase
{
    public function testAuthUrl(): void
    {
        $name = 'yandex';
        $state = 'sTaTe';

        $provider1 = $this->createMock(Provider::class);
        $provider1->expects(self::any())->method('isFor')->with($name)->willReturn(false);
        $provider1->expects(self::never())->method('generateAuthUrl')->with($state);

        $provider2 = $this->createMock(Provider::class);
        $provider2->expects(self::any())->method('isFor')->with($name)->willReturn(true);
        $provider2->expects(self::once())->method('generateAuthUrl')->with($state)->willReturn($url2 = 'http://p2');

        $client = new OAuthClient([$provider1, $provider2]);

        self::assertSame($url2, $client->generateAuthUrl($name, $state));
    }

    public function testIdentity(): void
    {
        $name = 'google';
        $code = 'c-o-d-e';

        $identity = new Identity('id', 'email@app.test');

        $provider1 = $this->createMock(Provider::class);
        $provider1->expects(self::any())->method('isFor')->with($name)->willReturn(false);
        $provider1->expects(self::never())->method('getIdentity')->with($code);

        $provider2 = $this->createMock(Provider::class);
        $provider2->expects(self::any())->method('isFor')->with($name)->willReturn(true);
        $provider2->expects(self::once())->method('getIdentity')->with($code)->willReturn($identity);

        $client = new OAuthClient([$provider1, $provider2]);

        self::assertSame($identity, $client->getIdentity($name, $code));
    }
}
