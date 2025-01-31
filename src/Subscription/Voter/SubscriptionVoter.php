<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Voter;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriptionInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class SubscriptionVoter extends Voter
{
    public const SHOW = 'show';

    public const EDIT = 'edit';

    protected function supports(
        string $attribute,
        mixed $subject,
    ): bool {
        if (false === $subject instanceof SubscriptionInterface) {
            return false;
        }

        $attributes = [
            self::SHOW,
            self::EDIT,
        ];

        if (false === \in_array($attribute, $attributes)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token,
    ): bool {
        $user = $token->getUser();

        if (false === $user instanceof ShopUserInterface) {
            return false;
        }

        /** @var SubscriptionInterface $subscription */
        $subscription = $subject;

        return match ($attribute) {
            self::SHOW => $this->canShow($subscription, $user),
            self::EDIT => $this->canEdit($subscription, $user),
            default => false,
        };
    }

    private function canShow(
        SubscriptionInterface $subscription,
        ShopUserInterface $shopUser,
    ): bool {
        $subscriptionShopUser = $subscription->getOwner()?->getSyliusCustomer()?->getUser();

        if (null === $subscriptionShopUser) {
            return false;
        }

        if ($subscriptionShopUser->getId() !== $shopUser->getId()) {
            return false;
        }

        return true;
    }

    private function canEdit(
        SubscriptionInterface $subscription,
        ShopUserInterface $shopUser,
    ): bool {
        return $this->canShow($subscription, $shopUser);
    }
}
