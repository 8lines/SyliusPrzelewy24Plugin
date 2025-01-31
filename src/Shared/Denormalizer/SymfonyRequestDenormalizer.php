<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Denormalizer;

use Symfony\Component\HttpFoundation\Request;

final readonly class SymfonyRequestDenormalizer implements SymfonyRequestDenormalizerInterface
{

    public function denormalize(array $httpRequest): ?Request
    {
        if (0 === \count($httpRequest)) {
            return null;
        }

        /** @var string|null $uri */
        $uri = $httpRequest['uri'] ?? null;

        /** @var string|null $method */
        $method = $httpRequest['method'] ?? null;

        if (null === $uri || null === $method) {
            return null;
        }

        /** @var array $query */
        $query = $httpRequest['query'] ?? [];

        /** @var array $request */
        $request = $httpRequest['request'] ?? [];

        /** @var string|null $clientIp */
        $clientIp = $httpRequest['client-ip'] ?? null;

        /** @var array $headers */
        $headers = $httpRequest['headers'] ?? [];

        if (null !== $clientIp) {
            $headers['REMOTE_ADDR'] = $clientIp;
        }

        /** @var string|null $content */
        $content = $httpRequest['content'] ?? null;

        return Request::create(
            uri: $uri,
            method: $method,
            parameters: \array_merge($query, $request),
            server: $headers,
            content: $content,
        );
    }
}
