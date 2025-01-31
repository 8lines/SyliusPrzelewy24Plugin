<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\EventListener\Workflow\Payment;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Creator\CardCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use Przelewy24\Przelewy24;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Workflow\Event\CompletedEvent;
use Webmozart\Assert\Assert;

final readonly class CreateCardIfNotExistsListener
{
    public function __construct(
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
        private CardCreatorInterface $cardCreator,
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

        $payload->validateNotNull(['orderId']);

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        /** @var Przelewy24 $przelewy24 */
        $przelewy24 = $this->paymentApiClientProvider->provideFromPaymentMethod(
            paymentMethod: $paymentMethod,
        );

        $card = $przelewy24->cards()->info(
            orderId: $payload->orderId(),
        );

        /** @var SyliusCustomerAsSubscriberInterface $customer */
        $customer = $order->getCustomer();

        Assert::notNull(
            value: $customer,
            message: 'CustomerAsSubscriber cannot be null.'
        );

        $this->cardCreator->createForCustomerIfNotExists(
            customer: $customer,
            refId: $card->refId(),
            mask: $card->mask(),
            date: $card->cardDate(),
        );
    }
}
