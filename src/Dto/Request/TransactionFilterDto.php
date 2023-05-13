<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use DateTime;

/**
 * Class TransactionFilterDto.
 */
class TransactionFilterDto implements DtoInterface
{
    /**
     * @var DateTime|null
     */
    protected $startDate;

    /**
     * @var DateTime|null
     */
    protected $endDate;

    /**
     * @var int|null
     */
    protected $trnType;

    /**
     * @var array|null
     */
    protected $trnTypes;

    /**
     * @var int|null
     */
    protected $status;

    /**
     * @var array|null
     */
    protected $statuses;

    /**
     * @var string|null
     */
    protected $user;

    /**
     * @var string|null
     */
    protected $merchant;

    /**
     * @var string|null
     */
    protected $deviceId;

    /**
     * @var string|null
     */
    protected $tid;

    /**
     * @var string|null
     */
    protected $mid;

    /**
     * @var string|null
     */
    protected $remoteServiceTransaction;

    /**
     * @var int|null
     */
    protected $page;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @var string|null
     */
    protected $processor;

    /**
     * @var string|null
     */
    protected $channel;

    /**
     * 3DS
     *
     * @var bool|null
     */
    protected $threeDs;
    
    /**
     * @var string|null
     */
    protected $cardType;

    /**
     * @var array|null
     */
    protected $cardTypes;

    /**
     * @var array|null
     */
    protected $sort;

    /**
     * @var string|null
     */
    private $acquirerCode;

    /**
     * @var bool|null
     */
    private ?bool $exactDate = false;

    /**
     * @var string|null
     */
    private $currency;

    private ?string $retrievalReferenceNumber = null;

    private ?string $trnKey = null;


    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    /**
     * @param DateTime|null $startDate
     */
    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    /**
     * @param DateTime|null $endDate
     */
    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return int|null
     */
    public function getTrnType(): ?int
    {
        return $this->trnType;
    }

    /**
     * @param int|null $trnType
     */
    public function setTrnType(?int $trnType): void
    {
        $this->trnType = $trnType;
    }

    /**
     * @return array|null
     */
    public function getTrnTypes(): ?array
    {
        return $this->trnTypes;
    }

    /**
     * @param array|null $trnTypes
     */
    public function setTrnTypes(?array $trnTypes): void
    {
        $this->trnTypes = $trnTypes;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param string|null $user
     */
    public function setUser(?string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getMerchant(): ?string
    {
        return $this->merchant;
    }

    /**
     * @param string|null $merchant
     */
    public function setMerchant(?string $merchant): void
    {
        $this->merchant = $merchant;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     */
    public function setPage(?int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return array|null
     */
    public function getStatuses(): ?array
    {
        return $this->statuses;
    }

    /**
     * @param array|null $statuses
     */
    public function setStatuses(?array $statuses): void
    {
        $this->statuses = $statuses;
    }

    /**
     * @return string|null
     */
    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    /**
     * @param string|null $deviceId
     */
    public function setDeviceId(?string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    /**
     * @return string|null
     */
    public function getTid(): ?string
    {
        return $this->tid;
    }

    /**
     * @param string|null $tid
     */
    public function setTid(?string $tid): void
    {
        $this->tid = $tid;
    }

    /**
     * @return string|null
     */
    public function getMid(): ?string
    {
        return $this->mid;
    }

    /**
     * @param string|null $mid
     */
    public function setMid(?string $mid): void
    {
        $this->mid = $mid;
    }

    /**
     * @return string|null
     */
    public function getRemoteServiceTransaction(): ?string
    {
        return $this->remoteServiceTransaction;
    }

    /**
     * @param string|null $remoteServiceTransaction
     */
    public function setRemoteServiceTransaction(?string $remoteServiceTransaction): void
    {
        $this->remoteServiceTransaction = $remoteServiceTransaction;
    }

    /**
     * @return string|null
     */
    public function getProcessor(): ?string
    {
        return $this->processor;
    }
    
    /**
     * @param string|null $processor
     */
    public function setProcessor(?string $processor): void
    {
        $this->processor = $processor;
    }

    /**
     * @return string|null
     */
    public function getChannel(): ?string
    {
        return $this->channel;
    }
    
    /**
     * @param string|null $channel
     */
    public function setChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return bool|null
     */
    public function getThreeDs(): ?bool
    {
        return $this->threeDs;
    }

    /**
     * @param bool|null $threeDs
     */
    public function setThreeDs(bool $threeDs): void
    {
        $this->threeDs = $threeDs;
    }

    /**
     * @return string|null
     */
    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    /**
     * @param string|null $cardType
     */
    public function setCardType(?string $cardType): void
    {
        $this->cardType = $cardType;
    }

    /**
     * @return array|null
     */
    public function getCardTypes(): ?array
    {
        return $this->cardTypes;
    }

    /**
     * @param array|null $cardTypes
     */
    public function setCardTypes(?array $cardTypes): void
    {
        $this->cardTypes = $cardTypes;
    }

    /**
     * @return array|null
     */
    public function getSort(): ?array
    {
        return $this->sort;
    }

    /**
     * @param array|null $sort
     */
    public function setSort(?array $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return string|null
     */
    public function getAcquirerCode(): ?string
    {
        return $this->acquirerCode;
    }

    /**
     * @param string|null $acquirerCode
     */
    public function setAcquirerCode(?string $acquirerCode): void
    {
        $this->acquirerCode = $acquirerCode;
    }

    /**
     * @return bool|null
     */
    public function getExactDate(): ?bool
    {
        return $this->exactDate;
    }

    /**
     * @param bool|null $exactDate
     */
    public function setExactDate(?bool $exactDate): void
    {
        $this->exactDate = $exactDate;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string|null
     */
    public function getRetrievalReferenceNumber(): ?string
    {
        return $this->retrievalReferenceNumber;
    }

    /**
     * @param string|null $retrievalReferenceNumber
     */
    public function setRetrievalReferenceNumber(?string $retrievalReferenceNumber): void
    {
        $this->retrievalReferenceNumber = $retrievalReferenceNumber;
    }

    /**
     * @return string|null
     */
    public function getTrnKey(): ?string
    {
        return $this->trnKey;
    }

    /**
     * @param string|null $trnKey
     */
    public function setTrnKey(?string $trnKey): void
    {
        $this->trnKey = $trnKey;
    }
}
