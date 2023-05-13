<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AnalyticsDto implements DtoInterface
{
    const PERIOD_DAILY   = 'daily';
    const PERIOD_WEEKLY  = 'weekly';
    const PERIOD_MONTHLY = 'monthly';

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Choice({"daily", "weekly", "monthly"})
     */
    protected $type;

    /**
     * @var \DateTimeInterface|null
     * @Assert\NotNull()
     */
    protected $date;

    /**
     * @var string|null
     * @Assert\NotBlank
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
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface|null $date
     */
    public function setDate(?\DateTimeInterface $date): void
    {
        $this->date = $date;
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
}
