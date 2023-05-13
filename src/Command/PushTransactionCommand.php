<?php

namespace App\Command;

use App\Entity\Transaction;
use App\Manager\TransactionManager;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Phos\Helper\SerializationTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PushTransactionCommand extends Command
{
    use SerializationTrait;
    protected static $defaultName = 'app:push-transaction';

    private EntityManagerInterface $entityManager;
    private NotificationService $notificationService;

    public function __construct(EntityManagerInterface $entityManager, NotificationService $notificationService)
    {
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;

        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Transaction id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $transactionId = $input->getOption('id');

        if ($transactionId) {
            /** @var Transaction $transaction */
            $transaction = $this->entityManager->getRepository(Transaction::class)->find($transactionId);
            if ($transaction) {
                $this->notificationService->dispatchMessage($this->serialize($transaction), 'phos@phos.cloud');
                $io->success('Processed transaction with id: ' . $transactionId);
            }
        }


        return Command::SUCCESS;
    }
}
