<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Controller;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Subscription\ProcessAddCardRequest;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Factory\AddCardRequestFactoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\AddCardRequestRepositoryInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Resolver\PaymentMethod\SubscriptionPaymentMethodResolverInterface;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Bundle\PaymentBundle\Normalizer\SymfonyRequestNormalizerInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final readonly class ProcessAddCardRequestController
{
    public function __construct(
        private UserContextInterface $userContext,
        private SymfonyRequestNormalizerInterface $symfonyRequestNormalizer,
        private SubscriptionPaymentMethodResolverInterface $subscriptionPaymentMethodResolver,
        private AddCardRequestFactoryInterface $addCardRequestFactory,
        private AddCardRequestRepositoryInterface $addCardRequestRepository,
        private MessageBusInterface $commandBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(Request $request): JsonResponse
    {
        /** @var ShopUserInterface|null $shopUser */
        $shopUser = $this->userContext->getUser();

        /** @var CustomerInterface $customer */
        $customer = $shopUser?->getCustomer();

        Assert::notNull(
            value: $customer,
            message: 'Customer not found.',
        );

        $httpRequest = $this->symfonyRequestNormalizer->normalize(
            request: $request,
        );

        $addCardRequest = $this->addCardRequestFactory->createCaptureRequest(
            customer: $customer,
            paymentMethod: $this->subscriptionPaymentMethodResolver->resolve(),
            parameters: $request->toArray() ?? [],
            httpRequest: $httpRequest,
        );

        $this->addCardRequestRepository->add($addCardRequest);

        $this->commandBus->dispatch(new ProcessAddCardRequest(
            hash: $addCardRequest->getHash()->toString(),
        ));

        $addCardRequest = $this->addCardRequestRepository->find(
            id: $addCardRequest->getId(),
        );

        return new JsonResponse(
            data: $addCardRequest->getResponse(),
            status: Response::HTTP_OK,
        );
    }
}
