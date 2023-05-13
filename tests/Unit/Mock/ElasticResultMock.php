<?php

namespace App\Tests\Unit\Mock;

use Elastica\Response;

class ElasticResultMock
{
    public static function realObject(): Response
    {
        return new Response([
            'aggregations' => [
                'total_amount' => ['value' => 12323.5],
                'average_amount' => ['value' => 56.34],
                'total_count' => ['value' => 45],
                'per_step' => [
                    'buckets' => []
                ]
            ]
        ]);
    }
}