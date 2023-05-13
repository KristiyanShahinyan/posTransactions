<?php

namespace App\Command;

use App\Message\ReconciliationMessage;
use App\Repository\ReconciliationRepository;
use App\Repository\TransactionRepository;
use Aws\S3\S3Client;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Phos\Helper\SerializationTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class ReconciliationCommand extends Command
{
    use SerializationTrait;
    protected static $defaultName = 'app:reconciliation';

    private MessageBusInterface $messageBus;
    private S3Client $s3Client;
    private ReconciliationRepository $reconciliationRepository;
    private TransactionRepository $transactionRepository;
    private EntityManagerInterface $entityManager;

    private const DEFAULT_BATCH_SIZE = 500;

    public function __construct(MessageBusInterface $messageBus, S3Client $s3Client,
                                ReconciliationRepository $reconciliationRepository, TransactionRepository $transactionRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->messageBus = $messageBus;
        $this->s3Client = $s3Client;
        $this->reconciliationRepository = $reconciliationRepository;
        $this->transactionRepository = $transactionRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Push batches of transactions in SQS for pos-service consummation')
            ->addOption('acquirer', null, InputOption::VALUE_OPTIONAL, 'acquirer')
            ->addOption('affiliate', null, InputOption::VALUE_OPTIONAL, 'affiliate')
            ->addOption('from', null, InputOption::VALUE_OPTIONAL, 'From Date format: Y-m-d H:i:s')
            ->addOption('to', null, InputOption::VALUE_OPTIONAL, 'To Date format: Y-m-d H:i:s')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $startDate = $input->getOption('from');
        $endDate = $input->getOption('to');
        $acquirer = $input->getOption('acquirer');
        $affiliate = $input->getOption('affiliate');
        $message = new ReconciliationMessage();

        if (!$startDate) {
            $startDate = (new DateTime())->modify('- 24 hours')->format('Y-m-d H:i:s');
        }

        if (!$endDate) {
            $endDate = (new DateTime())->format('Y-m-d H:i:s');
        }

        $filter = [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if ($acquirer) {
            $reconciliation = $this->reconciliationRepository->findOneBy(['acquirer'=>$acquirer]);
            $fileName = $acquirer;
            $message->setAcquirerCode($acquirer);
            $filter['acquirer'] = $acquirer;
        } else {
            $reconciliation = $this->reconciliationRepository->findByAffiliate($affiliate);
            $fileName = $affiliate;
            $message->setAffiliateCode($affiliate);
            $filter['affiliate'] = $reconciliation->getAffiliate();
        }

        if (!$reconciliation) {
            $io->error('No configuration for the given acquirer/affiliate.');
            return Command::FAILURE;
        }

        if (!$size = $reconciliation->getBatchSize()){
            $size = self::DEFAULT_BATCH_SIZE;
        }

        $io->writeln(
            sprintf('Reconciliation started with options: acquirer: %s, affiliate: %s StartDate: %s, EndDate :%s, Size: %s,',
                $acquirer, $affiliate, $startDate, $endDate, $size)
        );

        $transactions = $this->transactionRepository->getTransactionsForReconciliation($filter);

        $transactions = $this->handleOptions($reconciliation->getOptions(), $transactions);

        if (array_key_exists('groupBy', $reconciliation->getOptions())) {
            $batches = $transactions;
        } else {
            $batches = array_chunk($transactions, $size);
        }
        $message->setReplacePan($reconciliation->getReplacePan());
        $message->setCompress($reconciliation->getCompress());

        foreach($batches as $batch) {
            $tempFileName = $fileName;
            $batchNum = $reconciliation->incrementBatch();
            $tempFileName .= (new DateTime())->format('ymdHis:v') . '_' . $batchNum;
            $message->setFilename($tempFileName);
            $message->setBatch($batchNum);
            $this->uploadFile($tempFileName, $batch);
            $this->messageBus->dispatch($message);

            $this->markTransactionsAsReconciled($batch, $batchNum);
            $io->writeln(sprintf('Batch #%s was processed', $batchNum));
        }

        $reconciliation->setLastGenerated(new DateTime());
        $this->entityManager->persist($reconciliation);
        $this->entityManager->flush();

        $io->success(count($transactions).' transactions has been successfully processed');

        return Command::SUCCESS;
    }

    private function cleanVoid(array $transactions): array
    {
        foreach ($transactions as $index => $transaction) {
            if ($transaction['transactionType'] === 17) {
                $linkedTransaction = $transaction['linkedTransaction']['trnKey'];
                unset($transactions[$index]);
                foreach ($transactions as $index2 => $transaction2) {
                    if ($transaction2['trnKey'] === $linkedTransaction) {
                        unset($transactions[$index2]);
                    }
                }
            }
        }

        return $transactions;
    }

    private function groupBy(string $key, array $data): array
    {
        $result = [];

        foreach($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            }
        }

        return $result;
    }

    //TODO: Generalise
    private function handleOptions(array $options, array $transactions): array
    {
        foreach ($options as $index => $option) {
            if ($index === 'cleanVoid') {
                $transactions = $this->cleanVoid($transactions);
            } elseif ($index === 'groupBy') {
                $transactions = $this->groupBy($option, $transactions);
            }
        }

        return $transactions;
    }

    private function uploadFile($fileName, $content): void
    {
        $this->s3Client->putObject([
            'Bucket' => getenv('RECONCILE_BUCKET'),
            'Key' => $fileName,
            'Body' => $this->serialize($content, [AbstractObjectNormalizer::SKIP_NULL_VALUES]),
        ]);
    }

    private function markTransactionsAsReconciled(array $transactions, int $batchNum) {
        $trnIds = [];

        foreach ($transactions as $transaction) {
            $trnIds[] = $transaction['id'];
        }
        $this->transactionRepository->markTransactionsAsReconciled($trnIds, $batchNum);
    }
}
