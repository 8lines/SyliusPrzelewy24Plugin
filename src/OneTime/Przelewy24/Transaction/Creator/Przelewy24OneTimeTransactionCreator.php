<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\OneTime\Przelewy24\Transaction\Creator;

use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\TransactionPayloadTokenAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Assigner\TransactionResponseGatewayUrlAssignerInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\CreatableTransactionRequest;
use BitBag\SyliusPrzelewy24Plugin\Shared\Creator\TransactionCreatorInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Entity\TransactionalPaymentRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Provider\PaymentApiClientProviderInterface;
use BitBag\SyliusPrzelewy24Plugin\Shared\Przelewy24\Transaction\Creator\Przelewy24TransactionCreatorTrait;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Webmozart\Assert\Assert;

final readonly class Przelewy24OneTimeTransactionCreator implements TransactionCreatorInterface
{
    use Przelewy24TransactionCreatorTrait;

    public function __construct(
        private PaymentApiClientProviderInterface $paymentApiClientProvider,
        private TransactionPayloadTokenAssignerInterface $transactionPayloadTokenAssigner,
        private TransactionResponseGatewayUrlAssignerInterface $transactionResponseGatewayUrlAssigner,
        private PaymentDescriptionProviderInterface $paymentDescriptionProvider,
    ) {
    }

    public function create(CreatableTransactionRequest $request): void
    {
        /** @var TransactionalPaymentRequestInterface $request */

        Assert::isInstanceOf(
            value: $request,
            class: TransactionalPaymentRequestInterface::class,
            message: 'Request must be instance of TransactionalPaymentRequestInterface.',
        );

        $this->registerTransaction(
            request: $request,
            description: $this->paymentDescriptionProvider->getPaymentDescription($request->getPayment()),
        );
    }
}
