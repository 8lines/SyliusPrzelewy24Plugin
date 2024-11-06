<?php

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Subscription\Cli;

use BitBag\SyliusPrzelewy24Plugin\Subscription\Processor\Przelewy24SubscriptionAwaitingPaymentsProcessorInterface;
use BitBag\SyliusPrzelewy24Plugin\Subscription\Processor\Przelewy24SubscriptionScheduleIntervalCompletionProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

final class ProcessSubscriptions extends Command
{
    private const COMMAND_ID = 'bitbag:przelewy24:process-subscriptions';
    private const COMMAND_NAME = 'bitbag:przelewy24:process-subscriptions';

    private SymfonyStyle $io;

    public function __construct(
        private readonly Przelewy24SubscriptionScheduleIntervalCompletionProcessorInterface $przelewy24SubscriptionScheduleIntervalCompletionProcessor,
        private readonly Przelewy24SubscriptionAwaitingPaymentsProcessorInterface $przelewy24SubscriptionAwaitingPaymentsProcessor,
    ) {
        parent::__construct(self::COMMAND_NAME);
    }

    protected function configure(): void
    {
        $this->setDescription('Process subscriptions intervals completion and recurring payments.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start(self::COMMAND_ID);

        $this->io->title('Przelewy24 - subscription processing');

        try {
            $this->io->writeln('Processing subscription intervals completion...');
            $this->przelewy24SubscriptionScheduleIntervalCompletionProcessor->process();

            $this->io->writeln('Processing recurring payments...');
            $this->przelewy24SubscriptionAwaitingPaymentsProcessor->process();

            $this->io->success('Subscriptions processed successfully!');

        } catch (\Exception $exception) {
            $this->io->error('An error occurred during processing subscriptions.');
            $this->io->error($exception->getMessage());

            return Command::FAILURE;
        }

        $stopwatchEvent = $stopwatch->stop(self::COMMAND_ID);

        if (true === $output->isVerbose()) {
            $this->io->comment(
                \sprintf(
                    'Duration: %.2f ms / Memory: %.2f MB',
                    $stopwatchEvent->getDuration(),
                    $stopwatchEvent->getMemory() / (1024 ** 2)
                )
            );
        }

        return Command::SUCCESS;
    }
}
