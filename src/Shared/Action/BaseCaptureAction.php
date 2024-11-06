<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Shared\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;
use Payum\Core\Security\TokenInterface;

abstract class BaseCaptureAction implements ActionInterface, GatewayAwareInterface, GenericTokenFactoryAwareInterface
{
    use GatewayAwareTrait;
    use GenericTokenFactoryAwareTrait;

    protected function initializeTokensAndAssignSessionId(Capture $request): void {
        $model = ArrayObject::ensureArrayObject($request->getModel());

        /** @var TokenInterface $token */
        $token = $request->getToken();

        $notifyToken = $this->tokenFactory->createNotifyToken(
            gatewayName: $token->getGatewayName(),
            model: $token->getDetails(),
        );

        $model['sessionId'] = uniqid();
        $model['urlReturn'] = $token->getAfterUrl();
        $model['urlStatus'] = $notifyToken->getTargetUrl();
    }

    public function supports($request): bool
    {
        return $request instanceof Capture
            && $request->getModel() instanceof \ArrayAccess;
    }
}
