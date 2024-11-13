<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Factory;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CustomerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24SubscriptionConfigurationInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\RecurringOrderInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24CreditCardRepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

final class Przelewy24SubscriptionConfigurationFactory implements Przelewy24SubscriptionConfigurationFactoryInterface
{
    public function __construct(
        private readonly FactoryInterface $decoratedFactory,
        private readonly RouterInterface $router,
        private readonly Przelewy24CreditCardRepositoryInterface $przelewy24CreditCardRepository,
    ) {
    }

    public function createNew(): Przelewy24SubscriptionConfigurationInterface
    {
        return $this->decoratedFactory->createNew();
    }

    public function createFromRecurringOrder(
        RecurringOrderInterface $recurringOrder,
    ): Przelewy24SubscriptionConfigurationInterface {
        Assert::notNull($recurringOrder->getLastPayment());
        Assert::notNull($recurringOrder->getCustomer());

        $cardRefId = $recurringOrder->getLastPayment()->getDetails()['cardRefId'] ?? null;

        /** @var CustomerInterface $syliusCustomer */
        $syliusCustomer = $recurringOrder->getCustomer();

        Assert::notNull(
            value: $cardRefId,
            message: 'Recurring payment must have cardRefId',
        );

        $creditCard = $this->przelewy24CreditCardRepository->findByCardRefIdAndPrzelewy24CustomerId(
            cardRefId: $cardRefId,
            przelewy24CustomerId: $syliusCustomer->getPrzelewy24Customer()->getId(),
        );

        Assert::notNull(
            value: $creditCard,
            message: 'Credit card not found for given cardRefId and customer',
        );

        Assert::notNull($recurringOrder->getPrzelewy24RecurringProduct());

        $przelewy24RecurringProduct = $recurringOrder->getPrzelewy24RecurringProduct();

        Assert::notNull($przelewy24RecurringProduct->getRecurringTimes());
        Assert::notNull($przelewy24RecurringProduct->getRecurringIntervalInDays());

        Assert::notNull($recurringOrder->getTotal());
        Assert::notNull($recurringOrder->getCurrencyCode());

        $configuration = $this->createNew();

        $configuration->setRecurringTimes($przelewy24RecurringProduct->getRecurringTimes());
        $configuration->setRecurringIntervalInDays($przelewy24RecurringProduct->getRecurringIntervalInDays());
        $configuration->setCreditCard($creditCard);
        $configuration->setHostName($this->router->getContext()->getHost());
        $configuration->setLocaleCode($recurringOrder->getLocaleCode());

        return $configuration;
    }
}
