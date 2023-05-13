<?php

namespace App\Command;

use App\Manager\TransactionManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CheckVoidableTransactionsCommand extends Command
{
    protected static $defaultName = 'app:check-voidable-transactions';

    private TransactionManager $transactionManager;

    public function __construct(TransactionManager $transactionManager)
    {
        $this->transactionManager = $transactionManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Checks for transactions by certain criteria and marks them as non-voidable')
            ->addOption('acquirer_code', null, InputOption::VALUE_REQUIRED, 'An acquirer institution identification code')
            ->addOption('affiliate', null, InputOption::VALUE_OPTIONAL, 'The name of an affiliate')
            ->addOption('instance', null, InputOption::VALUE_OPTIONAL, 'The name of an instance')
            ->addOption('date', null, InputOption::VALUE_OPTIONAL, 'The expiry date of the voidable transactions');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $acquirerCode = $input->getOption('acquirer_code');
        $affiliate = $input->getOption('affiliate');
        $instance = $input->getOption('instance');
        $date = $input->getOption('date');

        $rows = $this->transactionManager->updateTransactionsVoidable($acquirerCode, $affiliate, $instance, $date);
        $io->success("$rows transactions updated");

        return Command::SUCCESS;
    }
}