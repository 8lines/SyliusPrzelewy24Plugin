<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Creator;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\SyliusCustomerAsSubscriberInterface;

interface CardCreatorInterface
{
    public function createForCustomerIfNotExists(
        SyliusCustomerAsSubscriberInterface $customer,
        string                              $refId,
        string                              $mask,
        string                              $date,
    ): void;
}
