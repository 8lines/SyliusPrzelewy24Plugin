<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Assigner;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\ResponseAssignableTransactionRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Payload\AddCardPayload;
use Webmozart\Assert\Assert;

final readonly class AddCardPayloadNotifyRequestHashAssigner implements AddCardPayloadNotifyRequestHashAssignerInterface
{
    public function assign(
        ResponseAssignableTransactionRequestInterface $request,
        string $notifyRequestHash,
    ): void {
        /** @var AddCardRequestInterface $request */

        Assert::isInstanceOf(
            value: $request,
            class: AddCardRequestInterface::class,
            message: 'Invalid request type %s, expected %s',
        );

        /** @var AddCardPayload $payload */
        $payload = $request->getTransactionPayload();

        $payload->withNotifyRequestHash($notifyRequestHash);

        $request->setTransactionPayload($payload);
    }
}
