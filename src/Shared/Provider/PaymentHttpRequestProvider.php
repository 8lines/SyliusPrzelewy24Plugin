<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Provider;

use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final readonly class PaymentHttpRequestProvider implements PaymentHttpRequestProviderInterface
{
    public function provide(PaymentRequestInterface $paymentRequest): ?Request
    {
        $httpRequest = $paymentRequest->getPayload()['http_request'] ?? [];

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
