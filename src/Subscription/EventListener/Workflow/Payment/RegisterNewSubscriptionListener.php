<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\EventListener\Workflow\Payment;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Creator\SubscriptionCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\Workflow\Event\CompletedEvent;
use Webmozart\Assert\Assert;

final readonly class RegisterNewSubscriptionListener
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private SubscriptionCreatorInterface $subscriptionCreator,
    ) {
    }

    public function __invoke(CompletedEvent $event): void
    {
        /** @var PaymentInterface $payment */
        $payment = $event->getSubject();

        /** @var RecurringSyliusOrderInterface $order */
        $order = $payment->getOrder();

        Assert::isInstanceOf(
            value: $order,
            class: RecurringSyliusOrderInterface::class,
            message: 'SyliusOrder must be instance of %2$s, but is %s.'
        );

        if (false === $order->getRecurringPrzelewy24Order()->isRecurring()) {
            return;
        }

        $payload = $this->paymentPayloadProvider->provideFromPayment(
            payment: $payment,
        );

        $payload->validateNotNull(['initializingSubscription']);

        if (false === $payload->initializingSubscription()) {
            return;
        }

        $this->subscriptionCreator->createFromOrder(
            order: $order,
        );
    }
}
