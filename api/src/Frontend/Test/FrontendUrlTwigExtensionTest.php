<?php

declare(strict_types=1);

namespace App\Frontend\Test;

use App\Frontend\FrontendUrlGenerator;
use App\Frontend\FrontendUrlTwigExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * @internal
 */
#[CoversClass(FrontendUrlTwigExtension::class)]
final class FrontendUrlTwigExtensionTest extends TestCase
{
    public function testSuccess(): void
    {
        $frontend = $this->createMock(FrontendUrlGenerator::class);
        $frontend->expects(self::once())->method('generate')->with(
            self::equalTo('path'),
            self::equalTo(['a' => 1, 'b' => 2])
        )->willReturn('http://test/path?a=1&b=2');

        $twig = new Environment(new ArrayLoader([
            'page.html.twig' => '<p>{{ frontend_url(\'path\', {\'a\': 1, \'b\': 2}) }}</p>',
        ]));

        $twig->addExtension(new FrontendUrlTwigExtension($frontend));

        self::assertSame('<p>http://test/path?a=1&amp;b=2</p>', $twig->render('page.html.twig'));
    }
}
