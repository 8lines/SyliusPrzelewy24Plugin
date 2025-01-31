<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\NotificationRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Processor\TransactionNotificationProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Creator\CardCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use Przelewy24\Przelewy24;
use Webmozart\Assert\Assert;

final readonly class AddCardNotificationCardCreationProcessor implements TransactionNotificationProcessorInterface
{
    public function __construct(
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
        private CardCreatorInterface $cardCreator,
    ) {
    }

    public function process(NotificationRequestInterface $request): void
    {
        /** @var AddCardRequestInterface $request */

        Assert::isInstanceOf(
            value: $request,
            class: AddCardRequestInterface::class,
            message: 'Request must be instance of %2$s, but is %s.',
        );

        /** @var Przelewy24 $przelewy24 */
        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentMethod(
            paymentMethod: $request->getPaymentMethod(),
        );

        $card = $przelewy24->cards()->info(
            orderId: $request->getTransactionPayload()->orderId(),
        );

        /** @var SyliusCustomerAsSubscriberInterface $customer */
        $customer = $request->getCustomer();

        $this->cardCreator->createForCustomerIfNotExists(
            customer: $customer,
            refId: $card->refId(),
            mask: $card->mask(),
            date: $card->cardDate(),
        );
    }
}
