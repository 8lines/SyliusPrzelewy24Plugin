<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Controller;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\CardRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\SubscriptionRepositoryInterface;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final readonly class ChangeSubscriptionCardController
{
    public function __construct(
        private UserContextInterface $userContext,
        private SubscriptionRepositoryInterface $subscriptionRepository,
        private CardRepositoryInterface $cardRepository,
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

        $card = $this->cardRepository->findOneBy([
            'id' => $request->toArray()['cardId'] ?? null,
            'owner' => $customer->getPrzelewy24Subscriber(),
        ]);

        Assert::notNull(
            value: $card,
            message: 'Card not found.',
        );

        $subscription->getConfiguration()->setCard($card);

        $this->subscriptionRepository->add($subscription);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
