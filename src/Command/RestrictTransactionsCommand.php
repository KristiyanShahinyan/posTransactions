<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Manager\TransactionManager;

/*
 * Example usage:
 * php bin/console app:restrict-transactions 20
 * 
 */
class RestrictTransactionsCommand extends Command
{
    protected static $defaultName = 'app:restrict-transactions';
    
    protected const DEFAULT_PERIOD = 15;
    
    protected const TRANSACTION_TYPE_SALE = 12;

    private TransactionManager $transactionManager;

    public function __construct(TransactionManager $transactionManager)
    {
        $this->transactionManager = $transactionManager;
        
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setDescription('Restrict transactions to be not voidable after some period')
            ->addArgument('period', InputArgument::OPTIONAL, 'Period in days')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $period = $input->getArgument('period') ?? static::DEFAULT_PERIOD;
        $batchSize = 100;

        $io->writeln(sprintf('Period %s days:', $period));

        $filter = [
            'transactionType' => static::TRANSACTION_TYPE_SALE,
            'period' => $period
        ];
        
        $iterableResult = $this->transactionManager->repository->getRestrictedTransactions($filter);
        
        $i = 1;

        foreach ($iterableResult as $item) {
            $item->setVoidable(false);
            $item->setRefundableAmount(0);
            
            if (($i % $batchSize) === 0) {
                $this->transactionManager->em->flush();
                $this->transactionManager->em->clear();
                $io->writeln(sprintf('Batch of %s processed', $i));
            }
            ++$i;
        }
        
        $this->transactionManager->em->flush();
            
        $io->success('Transactions were updated successfully.');
            
        return Command::SUCCESS;
    }
}
