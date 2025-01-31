<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Processor;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Entity\AddCardRequestInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Transitions\AddCardRequestTransitions;
use Psr\Log\LoggerInterface;
use Sylius\Abstraction\StateMachine\StateMachineInterface;

final readonly class AddCardRequestProcessor implements AddCardRequestProcessorInterface
{
    public function __construct(
        private StateMachineInterface $stateMachine,
        private LoggerInterface $logger,
    ) {
    }

    public function process(
        AddCardRequestInterface $request,
        callable $action,
    ): void {
        $this->stateMachine->apply(
            subject: $request,
            graphName: AddCardRequestTransitions::GRAPH,
            transition: AddCardRequestTransitions::TRANSITION_PROCESS,
        );

        try {
            $action($request);

        } catch (\Exception $exception) {
            $this->logger->error(
                message: 'Add card request processing failed.',
                context: ['exception' => $exception],
            );

            $this->stateMachine->apply(
                subject: $request,
                graphName: AddCardRequestTransitions::GRAPH,
                transition: AddCardRequestTransitions::TRANSITION_FAIL,
            );

            return;
        }

        $this->stateMachine->apply(
            subject: $request,
            graphName: AddCardRequestTransitions::GRAPH,
            transition: AddCardRequestTransitions::TRANSITION_COMPLETE,
        );
    }
}
