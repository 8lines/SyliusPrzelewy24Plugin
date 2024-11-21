<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Voter;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\CardInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CardVoter extends Voter
{
    public const SHOW = 'show';

    protected function supports(
        string $attribute,
        mixed $subject,
    ): bool {
        if (false === $subject instanceof CardInterface) {
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

        /** @var CardInterface $card */
        $card = $subject;

        return match ($attribute) {
            self::SHOW => $this->canShow($card, $user),
            default => false,
        };
    }

    private function canShow(
        CardInterface $card,
        ShopUserInterface $shopUser,
    ): bool {
        $creditCardOwner = $card->getOwner();
        $ownerShopUser = $creditCardOwner->getSyliusCustomer()?->getUser();

        if (null === $ownerShopUser) {
            return false;
        }

        if ($ownerShopUser->getId() !== $shopUser->getId()) {
            return false;
        }

        return true;
    }
}
