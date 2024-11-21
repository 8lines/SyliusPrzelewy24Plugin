<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Applicator;

use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Webmozart\Assert\Assert;

final class PaymentFailedStateApplicator implements PaymentFailedStateApplicatorInterface
{
    public function __construct(
        private readonly StateMachineInterface $stateMachine,
        private readonly RepositoryInterface $syliusPaymentRepository,
    ) {
    }

    public function apply(PaymentInterface $payment): void
    {
        if (PaymentInterface::STATE_FAILED === $payment->getState()) {
            return;
        }

        $canApplyFailedTransition = $this->stateMachine->can(
            subject: $payment,
            graphName: PaymentTransitions::GRAPH,
            transition: PaymentTransitions::TRANSITION_FAIL,
        );

        Assert::true(
            value: $canApplyFailedTransition,
            message: 'Payment cannot be failed',
        );

        $this->stateMachine->apply(
            subject: $payment,
            graphName: PaymentTransitions::GRAPH,
            transition: PaymentTransitions::TRANSITION_FAIL,
        );

        $this->syliusPaymentRepository->add($payment);
    }
}
