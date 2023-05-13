<?php

namespace App\Tests\Unit\Mock;

use App\Dto\Request\AnalyticsBalanceFilterDto;
use DateTime;

class AnalyticsBalanceFilterDtoMock
{
    public static final function realObject(): AnalyticsBalanceFilterDto
    {
        $dto = new AnalyticsBalanceFilterDto();
        $dto->setType('monthly');
        $dto->setEndDate(new DateTime());
        $dto->setMerchantToken(uniqid());
        $dto->setStartDate(new DateTime());
        $dto->setTimeZone('UTC');

        return $dto;
    }
}