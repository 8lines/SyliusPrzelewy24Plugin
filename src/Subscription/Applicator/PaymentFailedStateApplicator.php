<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator;

use SM\Factory\Factory;
use SM\SMException;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Webmozart\Assert\Assert;

final class PaymentFailedStateApplicator implements PaymentFailedStateApplicatorInterface
{
    public function __construct(
        private readonly Factory $stateMachineFactory,
        private readonly RepositoryInterface $syliusPaymentRepository,
    ) {
    }

    /**
     * @throws SMException
     */
    public function apply(PaymentInterface $payment): void
    {
        if (PaymentInterface::STATE_FAILED === $payment->getState()) {
            return;
        }

        $paymentGraph = $this->stateMachineFactory->get(
            object: $payment,
            graph: PaymentTransitions::GRAPH,
        );

        $canApplyFailedTransition = $paymentGraph->can(
            transition: PaymentTransitions::TRANSITION_FAIL,
        );

        Assert::true(
            value: $canApplyFailedTransition,
            message: 'Payment cannot be failed',
        );

        $paymentGraph->apply(
            transition: PaymentTransitions::TRANSITION_FAIL,
        );

        $this->syliusPaymentRepository->add($payment);
    }
}
