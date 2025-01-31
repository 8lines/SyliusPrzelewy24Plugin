<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Controller;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Command\Subscription\HandleAddCardNotification;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Repository\AddCardRequestRepositoryInterface;
use Sylius\Bundle\PaymentBundle\Normalizer\SymfonyRequestNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class HandleAddCardNotificationController extends AbstractController
{
    public function __construct(
        private readonly AddCardRequestRepositoryInterface $addCardRequestRepository,
        private readonly SymfonyRequestNormalizer $symfonyRequestNormalizer,
        private readonly MessageBusInterface $commandBus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(Request $request): JsonResponse
    {
        $addCardRequest = $this->addCardRequestRepository->findOneBy([
            'hash' => $request->get('hash'),
            'action' => AddCardRequestInterface::ACTION_NOTIFY,
            'state' => AddCardRequestInterface::STATE_NEW,
        ]);

        Assert::notNull(
            value: $addCardRequest,
            message: 'Add card request with hash %s not found.',
        );

        $addCardRequest->setParameters(
            parameters: $this->symfonyRequestNormalizer->normalize($request),
        );

        $this->addCardRequestRepository->add($addCardRequest);

        $this->commandBus->dispatch(new HandleAddCardNotification(
            hash: $addCardRequest->getHash()->toString(),
        ));

        return new JsonResponse(status: Response::HTTP_OK);
    }
}
