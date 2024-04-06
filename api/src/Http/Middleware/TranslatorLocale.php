<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ptlis\ConNeg\Negotiation;
use Symfony\Component\Translation\Translator;

final class TranslatorLocale implements MiddlewareInterface
{
    /**
     * @param string[] $locales
     */
    public function __construct(
        private readonly Translator $translator,
        private readonly array $locales
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $locale = self::parseLocale(
            $request->getHeaderLine('Accept-Language'),
            $this->locales
        );

        if ($locale !== null && $locale !== '') {
            $this->translator->setLocale($locale);
        }

        return $handler->handle($request);
    }

    /**
     * @param string[] $allowed
     */
    private static function parseLocale(?string $accept, array $allowed): ?string
    {
        $default = $allowed[0] ?? null;

        if ($accept === null || $accept === '') {
            return $default;
        }

        $negotiation = new Negotiation();
        $locale = $negotiation->languageBest($accept, implode(',', $allowed));

        return \in_array($locale, $allowed, true) ? $locale : $default;
    }
}
