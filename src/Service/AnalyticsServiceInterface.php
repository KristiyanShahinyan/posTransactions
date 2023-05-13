<?php

namespace App\Service;

/**
 * Service to provide analytical data for transactions.
 */
interface AnalyticsServiceInterface
{
    /**
     * Returns analytical data for the requested pivot time and type.
     *
     * @return mixed
     */
    public function getAnalyticsData(string $userToken, \DateTime $pivotTime, string $type);
}
