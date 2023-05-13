<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;
use DateTimeInterface;

class AnalyticsBalanceFilterDto implements DtoInterface
{
    public const PERIOD_DAILY   = 'daily';
    public const PERIOD_WEEKLY  = 'weekly';
    public const PERIOD_MONTHLY = 'monthly';

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Choice(callback="getPeriods", message="Type is invalid, valid types are {{ choices }}")
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $merchantToken;
    
    /**
     * @var DateTimeInterface|null
     */
    protected $startDate;
    
    /**
     * @var DateTimeInterface|null
     */
    protected $endDate;
    
    /**
     * @var string|null
     * @Assert\Timezone
     */
    protected $timeZone;

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getMerchantToken(): ?string
    {
        return $this->merchantToken;
    }
    
    /**
     * @param string|null $merchantToken
     */
    public function setMerchantToken(?string $merchantToken): void
    {
        $this->merchantToken = $merchantToken;
    }    
    
    /**
     * @return DateTimeInterface|null
     */
    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @param DateTimeInterface|null $startDate
     */
    public function setStartDate(?DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }
    
    /**
     * @param DateTimeInterface|null $endDate
     */
    public function setEndDate(?DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }
    
    /**
     * @return string|null
     */
    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    /**
     * @param string|null $timeZone
     */
    public function setTimeZone(?string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }

    public function getPeriods(): array
    {
        return [
            self::PERIOD_DAILY,
            self::PERIOD_MONTHLY,
            self::PERIOD_WEEKLY
        ];
    }    
}
