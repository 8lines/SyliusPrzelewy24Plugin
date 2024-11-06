<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Voter;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\Przelewy24CreditCardInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class Przelewy24CreditCardVoter extends Voter
{
    public const SHOW = 'show';

    protected function supports(
        string $attribute,
        mixed $subject,
    ): bool {
        if (false === $subject instanceof Przelewy24CreditCardInterface) {
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

        /** @var Przelewy24CreditCardInterface $creditCard */
        $creditCard = $subject;

        return match ($attribute) {
            self::SHOW => $this->canShow($creditCard, $user),
            default => false,
        };
    }

    private function canShow(
        Przelewy24CreditCardInterface $creditCard,
        ShopUserInterface $shopUser,
    ): bool {
        $creditCardOwner = $creditCard->getOwner();
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
