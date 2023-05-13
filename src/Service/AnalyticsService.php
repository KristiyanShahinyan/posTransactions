<?php

namespace App\Service;

use App\Dto\Request\AnalyticsBalanceFilterDto;
use App\Util\AnalyticsIntervalHelperInterface;
use App\Util\IntervalHelperFactory;
use DatePeriod;
use DateTime;
use Elastica\Client;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Range;
use Elastica\Query\Term;
use Elastica\QueryBuilder\DSL\Aggregation;
use Elastica\Script\Script;
use LogicException;

/**
 * Service to provide analytical data for transactions.
 */
class AnalyticsService implements AnalyticsServiceInterface
{
    /**
     * @var IntervalHelperFactory
     */
    private $helperFactory;

    /**
     * @var Client
     */
    private $client;

    /**
     * AnalyticsService constructor.
     */
    public function __construct(IntervalHelperFactory $helperFactory, Client $client)
    {
        $this->helperFactory = $helperFactory;
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getAnalyticsData(string $userToken, DateTime $pivotTime, string $type)
    {
        $helper = $this->helperFactory->create($type);
        $period = $helper->getAnalyzedPeriod($pivotTime);

        /**
         * Build the Elastica query.
         */
        $range = new Range('pos_local_datetime', [
            'gte'       => $period->getStartDate()->format('Y-m-d\TH:i:s'),
            'lte'       => $period->getEndDate()->format('Y-m-d\TH:i:s')
        ]);

        $filterQuery = new BoolQuery();
        $filterQuery->addMust($range);
        $filterQuery->addFilter(
            (new BoolQuery())
                ->addMust(new Term(['status' => 1]))
                ->addMust(new Term(['user_token' => $userToken]))
        );

        // Query for average sale and sale count
        $filterQuery2 = new BoolQuery();
        $filterQuery2->addMust($range);
        $filterQuery2->addFilter(
            (new BoolQuery())
                ->addMust(new Term(['status' => 1]))
                ->addMust(new Query\Terms('transaction_type', [12, 18, 20]))
                ->addMust(new Term(['user_token' => $userToken]))
        );

        $aggregationFactory = new Aggregation();
        $scriptedAggr = $aggregationFactory->scripted_metric('total_amount',
            "state.transactions = []",
            "state.transactions.add(doc.transaction_type.value.compareTo('12') == 0 || doc.transaction_type.value.compareTo('18') == 0 || doc.transaction_type.value.compareTo('20') == 0 ? doc.amount.value : -1 * doc.amount.value)",
            "double profit = 0; for (t in state.transactions) { profit += t } return profit",
            "double profit = 0; for (a in states) { if(a == null) {profit += 0;} else { profit += a; } } return profit");

        $histogramAggregation = $aggregationFactory
            ->date_histogram('per_step', 'pos_local_datetime', $helper->getHistogramInterval())
            ->setFormat($helper->getHistogramDateFormat());
        $histogramAggregation->addAggregation($scriptedAggr);


        $query = new Query();
        $query
            ->setQuery($filterQuery)
            ->setSize(0)
            ->addAggregation($scriptedAggr)
            ->addAggregation($histogramAggregation);

        $query2 = new Query();
        $query2->setQuery($filterQuery2)
            ->setSize(0)
            ->addAggregation($aggregationFactory->value_count('total_count', 'pos_local_datetime'))
            ->addAggregation($aggregationFactory->avg('average_amount')->setField('amount'));


        $result = $this->client->getIndex('transactions')->search($query);
        $result2 = $this->client->getIndex('transactions')->search($query2);

        $data = $result->getResponse()->getData()['aggregations'];
        $data2 = $result2->getResponse()->getData()['aggregations'];

        $payload = [
            'amounts'      => [],
            'sales'        => $data['total_amount']['value'] ?? 0,
            'average'      => $data2['average_amount']['value'] ?? 0,
            'transactions' => $data2['total_count']['value'] ?? 0,
        ];

        // collect bucket indexes
        $availableBuckets = [];
        foreach ($data['per_step']['buckets'] as $idx => $bucket) {
            $availableBuckets[$helper->getFormattedIntervalIndex($bucket)] = $idx;
        }

        foreach ($period as $bucketStart) {
            $idx = $helper->getBucketIndexByDate($bucketStart);

            if (isset($availableBuckets[$idx])) {
                $bucket = $data['per_step']['buckets'][$availableBuckets[$idx]];

                $payload['amounts'][] = $bucket['total_amount']['value'];

                unset($availableBuckets[$idx]);
            } else {
                $payload['amounts'][] = 0;
            }
        }

        if (!empty($availableBuckets)) {
            throw new \LogicException(sprintf('There are unprocessed aggregation buckets: %s', var_export($availableBuckets, true)));
        }

        return $payload;
    }
    
    public function getAnalyticsStats(AnalyticsBalanceFilterDto $dto, int $status)
    {
        $helper = $this->helperFactory->create($dto->getType());
        
        $startDate = $dto->getStartDate() ? new DateTime($dto->getStartDate()->format('Y-m-d')) : null;
       
        $endDate = $dto->getEndDate()
            ? new DateTime($dto->getEndDate()->format('Y-m-d'))
            : new DateTime(null);
        
        $period = $helper->getStatsPeriod($endDate, $startDate);
        
        $data = $this->buildElasticSearchQuery($helper, $period, $endDate, $status, $dto->getMerchantToken());
        
        $payload = [
            'startDate' =>  $period->getStartDate()->format('d.m.Y'),
            'endDate' => $period->getEndDate()->format('d.m.Y'),
            'sales' => $data['total_amount']['value'] ?? 0,
            'average' => $data['average_amount']['value'] ?? 0,
            'transactions' => $data['total_count']['value'] ?? 0,
            'counts' => $this->getCountsPayload($helper, $period, $data)
        ];
        
        return $payload;
    }
    
    private function buildElasticSearchQuery(
        AnalyticsIntervalHelperInterface $helper,
        DatePeriod $period,
        DateTime $endDate,
        int $status,
        string $merchantToken
    ) {
        $filterQuery = new BoolQuery();
        $filterQuery->addMust(new Range('pos_local_datetime', [
            'gte'       => $period->getStartDate()->format('Y-m-d\TH:i:s'),
            'lte'       => $period->getEndDate()->format('Y-m-d\TH:i:s')
        ]));
        $filterQuery->addFilter(
            (new BoolQuery())
            ->addMust(new Term(['status' => $status])) // Approved & Declined
            ->addMust(new Query\Terms('transaction_type', [12, 18, 20]))
            ->addMust(new Term(['merchant' => $merchantToken]))
            );
        
        // Get counts
        $aggregationFactory = new Aggregation();
        
        $histogramAggregation = $aggregationFactory
        ->date_histogram('per_step', 'pos_local_datetime', $helper->getHistogramInterval())
        ->setFormat($helper->getHistogramDateFormat());

        $histogramAggregation->addAggregation($aggregationFactory->value_count('total', 'pos_local_datetime')->setField('amount'));
        
        $query = new Query();
        $query
        ->setQuery($filterQuery)
        ->setSize(0)
        ->addAggregation($aggregationFactory->value_count('total_count', 'pos_local_datetime'))
        ->addAggregation($aggregationFactory->sum('total_amount')->setField('amount'))
        ->addAggregation($aggregationFactory->avg('average_amount')->setField('amount'))
        ->addAggregation($histogramAggregation);
        
        $result = $this->client->getIndex('transactions')->search($query);
        
        return $result->getResponse()->getData()['aggregations'];
    }
    
    private function getCountsPayload(AnalyticsIntervalHelperInterface $helper, DatePeriod $period, array $data)
    {
        $counts = [];
        
        $availableBuckets = [];
        foreach ($data['per_step']['buckets'] as $idx => $bucket) {
            $availableBuckets[$helper->getFormattedIntervalIndex($bucket)] = $idx;
        }
        
        foreach ($period as $bucketStart) {
            $idx = $helper->getBucketIndexByDate($bucketStart);
            
            if (isset($availableBuckets[$idx])) {
                $bucket = $data['per_step']['buckets'][$availableBuckets[$idx]];
                
                $counts[$idx] = $bucket['total']['value'];
                
                unset($availableBuckets[$idx]);
            } else {
                $counts[$idx] = 0;
            }
        }
        
        if (!empty($availableBuckets)) {
            throw new LogicException(sprintf('There are unprocessed aggregation buckets: %s', var_export($availableBuckets, true)));
        }
        return $counts;
    }
}
