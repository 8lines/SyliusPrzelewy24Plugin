<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Controller;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\SubscriptionRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions\SubscriptionIntervalTransitions;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions\SubscriptionTransitions;
use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final readonly class CancelSubscriptionController
{
    public function __construct(
        private UserContextInterface $userContext,
        private SubscriptionRepositoryInterface $subscriptionRepository,
        private StateMachineInterface $stateMachine,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var ShopUserInterface|null $shopUser */
        $shopUser = $this->userContext->getUser();

        /** @var SyliusCustomerAsSubscriberInterface $customer */
        $customer = $shopUser?->getCustomer();

        Assert::notNull(
            value: $customer,
            message: 'Customer not found.',
        );

        $subscription = $this->subscriptionRepository->findOneBy([
            'id' => $request->get('id'),
            'owner' => $customer->getPrzelewy24Subscriber(),
        ]);

        Assert::notNull(
            value: $subscription,
            message: 'Subscription not found.',
        );

        foreach ($subscription->getSchedule()->getIntervals() as $interval) {
            if (false === $interval->isScheduled() || false === $interval->isFulfilled()) {
                continue;
            }

            $this->stateMachine->apply(
                subject: $interval,
                graphName: SubscriptionIntervalTransitions::GRAPH,
                transition: SubscriptionIntervalTransitions::TRANSITION_CANCEL,
            );
        }

        $subscription->getConfiguration()->setCard(null);

        if (false === $subscription->isCancelled()) {
            $this->stateMachine->apply(
                subject: $subscription,
                graphName: SubscriptionTransitions::GRAPH,
                transition: SubscriptionTransitions::TRANSITION_CANCEL,
            );
        }

        $this->subscriptionRepository->add($subscription);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
