<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Voter;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SubscriberInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class SubscriberVoter extends Voter
{
    public const SHOW = 'show';

    protected function supports(
        string $attribute,
        mixed $subject,
    ): bool {
        if (false === $subject instanceof SubscriberInterface) {
            return false;
        }

        $attributes = [
            self::SHOW,
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

        /** @var SubscriberInterface $subscriber */
        $subscriber = $subject;

        return match ($attribute) {
            self::SHOW => $this->canShow($subscriber, $user),
            default => false,
        };
    }

    private function canShow(
        SubscriberInterface $subscriber,
        ShopUserInterface $shopUser,
    ): bool {
        $subscriberShopUser = $subscriber->getSyliusCustomer()?->getUser();

        if (null === $subscriberShopUser) {
            return false;
        }

        if ($subscriberShopUser->getId() !== $shopUser->getId()) {
            return false;
        }

        return true;
    }
}
