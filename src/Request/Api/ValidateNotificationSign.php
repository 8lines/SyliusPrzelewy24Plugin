<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Request\Api;

use Payum\Core\Request\Generic;

class ValidateNotificationSign extends Generic
{
    private bool $valid;

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }
}
