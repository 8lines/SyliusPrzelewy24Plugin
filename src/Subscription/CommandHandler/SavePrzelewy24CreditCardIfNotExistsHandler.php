<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\CommandHandler;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\SavePrzelewy24CreditCardIfNotExistsCommand;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24CustomerInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\Przelewy24CreditCardFactoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24CreditCardRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\Przelewy24CustomerRepositoryInterface;
use Webmozart\Assert\Assert;

final class SavePrzelewy24CreditCardIfNotExistsHandler
{
    public function __construct(
        private readonly Przelewy24CreditCardRepositoryInterface $przelewy24CreditCardRepository,
        private readonly Przelewy24CustomerRepositoryInterface $przelewy24CustomerRepository,
        private readonly Przelewy24CreditCardFactoryInterface $przelewy24CreditCardFactory,
    ) {
    }

    public function __invoke(SavePrzelewy24CreditCardIfNotExistsCommand $command): void
    {
        $existsByCardRefIdAndSyliusCustomerId = $this->przelewy24CreditCardRepository->existsByCardRefIdAndPrzelewy24CustomerId(
            cardRefId: $command->cardRefId(),
            przelewy24CustomerId: $command->przelewy24CustomerId(),
        );

        if (true === $existsByCardRefIdAndSyliusCustomerId) {
            return;
        }

        /** @var Przelewy24CustomerInterface $przelewy24Customer */
        $przelewy24Customer = $this->przelewy24CustomerRepository->find(
            id: $command->przelewy24CustomerId(),
        );

        Assert::notNull(
            value: $przelewy24Customer,
            message: 'Przelewy24 customer not found',
        );

        $przelewy24CreditCard = $this->przelewy24CreditCardFactory->createUsingCardData(
            cardMask: $command->cardMask(),
            cardDate: $command->cardDate(),
            cardRefId: $command->cardRefId(),
        );

        $przelewy24Customer->addCreditCard(
            creditCard: $przelewy24CreditCard,
        );

        $this->przelewy24CustomerRepository->add($przelewy24Customer);
    }
}
