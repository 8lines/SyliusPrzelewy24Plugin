<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentPayloadProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionConfigurationInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringSyliusOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\CardRepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final readonly class SubscriptionConfigurationFactory implements SubscriptionConfigurationFactoryInterface
{
    public function __construct(
        private FactoryInterface $decoratedFactory,
        private RouterInterface $router,
        private CardRepositoryInterface $cardRepository,
        private PaymentPayloadProviderInterface $paymentPayloadProvider,
    ) {
    }

    public function createNew(): SubscriptionConfigurationInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createFromOrder(RecurringSyliusOrderInterface $order): SubscriptionConfigurationInterface
    {
        Assert::notNull(
            value: $order->getLastPayment(),
            message: 'SyliusOrder must have last payment',
        );

        $payload = $this->paymentPayloadProvider->provideFromPayment(
            payment: $order->getLastPayment(),
        );

        $payload->validateNotNull(['cardRefId']);

        Assert::notNull(
            value: $order->getCustomer(),
            message: 'SyliusOrder must have customer',
        );

        /** @var SyliusCustomerAsSubscriberInterface $customer */
        $customer = $order->getCustomer();

        $card = $this->cardRepository->findByRefIdAndSubscriberId(
            refId: $payload->cardRefId(),
            subscriberId: $customer->getPrzelewy24Subscriber()->getId(),
        );

        Assert::notNull(
            value: $card,
            message: 'Card not found for given cardRefId and customer',
        );

        Assert::notNull(
            value: $order->getRecurringPrzelewy24Product(),
            message: 'SyliusOrder must have przelewy24 recurring product',
        );

        $product = $order->getRecurringPrzelewy24Product();

        Assert::notNull(
            value: $product->getRecurringTimes(),
            message: 'Product must have recurring times set',
        );

        Assert::notNull(
            value: $product->getRecurringIntervalInDays(),
            message: 'Product must have recurring interval in days set',
        );

        Assert::notNull(
            value: $order->getCurrencyCode(),
            message: 'SyliusOrder must have currency code set',
        );

        $configuration = $this->createNew();

        $configuration->setRecurringTimes($product->getRecurringTimes());
        $configuration->setRecurringIntervalInDays($product->getRecurringIntervalInDays());
        $configuration->setCard($card);
        $configuration->setHostName($this->router->getContext()->getHost());
        $configuration->setLocaleCode($order->getLocaleCode());

        return $configuration;
    }
}
