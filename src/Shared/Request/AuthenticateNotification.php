<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Request;

use Payum\Core\Request\Generic;

class AuthenticateNotification extends Generic
{
    private bool $authenticated = false;

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    public function setAuthenticated(bool $authenticated): void
    {
        $this->authenticated = $authenticated;
    }
}
