<?php

namespace App\Dto\Response;

use App\Dto\DtoInterface;
use App\Entity\Transaction;
use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class TransactionDto implements DtoInterface
{
    /**
     * @var int|null
     *
     * @Groups({"phos","index","vpos"})
     */
    protected $id;

    /**
     * @var string|null
     * @Groups({"phos","index","vpos"})
     */
    protected $trnKey;

    /**
     * @var float|null
     * @Groups({"phos","index","vpos"})
     */
    protected $amount;

    /**
     * @var string|null
     * @Groups({"phos","index","vpos"})
     */
    protected $currency;

    /**
     * @var float|null
     * @Groups({"phos","index"})
     */
    protected $tipAmount;

    /**
     * @var DateTime|null
     * @Groups({"phos","index","vpos"})
     */
    protected $trnDate;

    /**
     * @var DateTime|null
     * @Groups({"phos","index"})
     */
    protected $posLocalDateTime;

    /**
     * @var string|null
     * @Groups({"phos","index","vpos"})
     */
    protected $user;

    /**
     * @var int|null
     * @Groups({"phos","index","vpos"})
     */
    protected $status;

    /**
     * @Groups({"phos","index"})
     */
    protected $terminalId;

    /**
     * @var string|null
     * @Groups({"phos","index","vpos"})
     */
    protected $card;

    /**
     * @var string|null
     * @Groups({"phos","index","vpos"})
     */
    protected $cardPan;

    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected $cardType;

    /**
     * @var int|null
     * @Groups({"phos","index"})
     */
    protected $mcc;

    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected $mccDescription;

    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected $merchant;

    /**
     * @Groups({"phos","index"})
     */
    protected $remoteServiceTransaction;

    /**
     * @Groups({"phos","index"})
     */
    protected $stan;

    /**
     * @Groups({"phos","index"})
     */
    protected $authCode;

    /**
     * @Groups({"phos","index"})
     */
    protected $deviceId;

    /**
     * @Groups({"phos","index"})
     */
    protected $errorCode;

    /**
     * @Groups({"phos","index"})
     */
    protected $applicationId;

    /**
     * @Groups({"phos","index"})
     */
    protected $applicationCryptogram;

    /**
     * @Groups({"phos","index"})
     */
    protected $cardAcceptorIdentCode;

    /**
     * @Groups({"phos","index"})
     */
    protected $transactionStamp;

    /**
     * @Groups({"phos","index"})
     */
    protected $refundableAmount;

    /**
     * @Groups({"phos","index"})
     */
    protected $voidable;

    /**
     * @Groups({"phos","index"})
     */
    protected $channel;

    /**
     * @var int
     * @Groups({"phos","index"})
     */
    protected $transactionType;

    /**
     * @var string|null
     */
    protected ?string $longitude;

    /**
     * @var string|null
     */
    protected ?string $latitude;

    /**
     * @var Transaction|null
     * @Groups({"phos","index"})
     */
    protected ?Transaction $linkedTransaction;
    
    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected ?string $processor;
   
    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected ?string $retrievalReferenceNumber;

    /**
     * @var int|null
     * @Groups({"phos","index"})
     */
    protected ?int $scaType;

    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected ?string $receivingInstitutionIdentificationCode;

    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected ?string $acquirerCode;

    /**
     * @var int|null
     * @Groups({"phos","index"})
     */
    protected ?int $cctiId;

    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected ?string $cardAcceptorName;

    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected ?string $cardAcceptorCountry;

    /**
     * @var string|null
     * @Groups({"phos","index"})
     */
    protected ?string $cardAcceptorCity;

    /**
     * @Groups({"phos","index"})
     */
    protected ?string $originalResponseCode = '';

    /**
     * @Groups({"phos","index"})
     */
    protected ?string $additionalResponseData = '';

    /**
     * @Groups({"phos","index"})
     */
    protected ?array $acquirerSpecificData = null;

    /**
     * @Groups({"phos","index"})
     */
    protected ?array $metadata = null;

    /**
     * @Groups({"phos","index"})
     */
    protected ?string $orderReference = null;

    /**
     * @Groups({"phos","index"})
     */
    private ?float $surchargeAmount = null;

    /**
     * @Groups({"phos","index"})
     */
    private ?int $paymentMethod = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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

    /**
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * @param float|null $amount
     */
    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
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
     * @return float|null
     */
    public function getTipAmount(): ?float
    {
        return $this->tipAmount;
    }

    /**
     * @param float|null $tipAmount
     */
    public function setTipAmount(?float $tipAmount): void
    {
        $this->tipAmount = $tipAmount;
    }

    /**
     * @return DateTime|null
     */
    public function getTrnDate(): ?DateTime
    {
        return $this->trnDate;
    }

    /**
     * @param DateTime|null $trnDate
     */
    public function setTrnDate(?DateTime $trnDate): void
    {
        $this->trnDate = $trnDate;
    }

    /**
     * @return DateTime|null
     */
    public function getPosLocalDateTime(): ?DateTime
    {
        return $this->posLocalDateTime;
    }

    /**
     * @param DateTime|null $posLocalDateTime
     */
    public function setPosLocalDateTime(?DateTime $posLocalDateTime): void
    {
        $this->posLocalDateTime = $posLocalDateTime;
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
     * @return mixed
     */
    public function getTerminalId()
    {
        return $this->terminalId;
    }

    /**
     * @param mixed $terminalId
     */
    public function setTerminalId($terminalId): void
    {
        $this->terminalId = $terminalId;
    }

    /**
     * @return string|null
     */
    public function getCard(): ?string
    {
        return $this->card;
    }

    /**
     * @param string|null $card
     */
    public function setCard(?string $card): void
    {
        $this->card = $card;
    }

    /**
     * @return string|null
     */
    public function getCardPan(): ?string
    {
        return $this->cardPan;
    }

    /**
     * @param string|null $cardPan
     */
    public function setCardPan(?string $cardPan): void
    {
        $this->cardPan = $cardPan;
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
     * @return int|null
     */
    public function getMcc(): ?int
    {
        return $this->mcc;
    }

    /**
     * @param int|null $mcc
     */
    public function setMcc(?int $mcc): void
    {
        $this->mcc = $mcc;
    }

    /**
     * @return string|null
     */
    public function getMccDescription(): ?string
    {
        return $this->mccDescription;
    }

    /**
     * @param string|null $mccDescription
     */
    public function setMccDescription(?string $mccDescription): void
    {
        $this->mccDescription = $mccDescription;
    }

    /**
     * @return mixed
     */
    public function getRemoteServiceTransaction()
    {
        return $this->remoteServiceTransaction;
    }

    /**
     * @param mixed $remoteServiceTransaction
     */
    public function setRemoteServiceTransaction($remoteServiceTransaction): void
    {
        $this->remoteServiceTransaction = $remoteServiceTransaction;
    }

    /**
     * @return mixed
     */
    public function getStan()
    {
        return $this->stan;
    }

    /**
     * @param mixed $stan
     */
    public function setStan($stan): void
    {
        $this->stan = $stan;
    }

    /**
     * @return mixed
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * @param mixed $authCode
     */
    public function setAuthCode($authCode): void
    {
        $this->authCode = $authCode;
    }

    /**
     * @return mixed
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @param mixed $deviceId
     */
    public function setDeviceId($deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     */
    public function setErrorCode($errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return mixed
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * @param mixed $applicationId
     */
    public function setApplicationId($applicationId): void
    {
        $this->applicationId = $applicationId;
    }

    /**
     * @return mixed
     */
    public function getApplicationCryptogram()
    {
        return $this->applicationCryptogram;
    }

    /**
     * @param mixed $applicationCryptogram
     */
    public function setApplicationCryptogram($applicationCryptogram): void
    {
        $this->applicationCryptogram = $applicationCryptogram;
    }

    /**
     * @return mixed
     */
    public function getCardAcceptorIdentCode()
    {
        return $this->cardAcceptorIdentCode;
    }

    /**
     * @param mixed $cardAcceptorIdentCode
     */
    public function setCardAcceptorIdentCode($cardAcceptorIdentCode): void
    {
        $this->cardAcceptorIdentCode = $cardAcceptorIdentCode;
    }

    /**
     * @return mixed
     */
    public function getTransactionStamp()
    {
        return $this->transactionStamp;
    }

    /**
     * @param mixed $transactionStamp
     */
    public function setTransactionStamp($transactionStamp): void
    {
        $this->transactionStamp = $transactionStamp;
    }

    /**
     * @return int
     */
    public function getTransactionType(): int
    {
        return $this->transactionType;
    }

    /**
     * @param int $transactionType
     */
    public function setTransactionType(int $transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return mixed
     */
    public function getRefundableAmount()
    {
        return $this->refundableAmount;
    }

    /**
     * @param mixed $refundableAmount
     */
    public function setRefundableAmount($refundableAmount): void
    {
        $this->refundableAmount = $refundableAmount;
    }

    /**
     * @return mixed
     */
    public function getVoidable()
    {
        return $this->voidable;
    }

    /**
     * @param mixed $voidable
     */
    public function setVoidable($voidable): void
    {
        $this->voidable = $voidable;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel($channel): void
    {
        $this->channel = $channel;
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
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param string|null $longitude
     */
    public function setLongitude(?string $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param string|null $latitude
     */
    public function setLatitude(?string $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return Transaction|null
     */
    public function getLinkedTransaction(): ?Transaction
    {
        return $this->linkedTransaction;
    }
    
    /**
     * @param Transaction|null $linkedTransaction
     */
    public function setLinkedTransaction(?Transaction $linkedTransaction): void
    {
        $this->linkedTransaction = $linkedTransaction;
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
    public function getRetrievalReferenceNumber():  ?string
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
     * @return int|null
     */
    public function getScaType(): ?int
    {
        return $this->scaType;
    }

    /**
     * @param int $scaType
     */
    public function setScaType(int $scaType): void
    {
        $this->scaType = $scaType;
    }

    /**
     * @return string|null
     */
    public function getReceivingInstitutionIdentificationCode(): ?string
    {
        return $this->receivingInstitutionIdentificationCode;
    }

    /**
     * @param string|null $receivingInstitutionIdentificationCode
     */
    public function setReceivingInstitutionIdentificationCode(?string $receivingInstitutionIdentificationCode): void
    {
        $this->receivingInstitutionIdentificationCode = $receivingInstitutionIdentificationCode;
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
     * @return int|null
     */
    public function getCctiId(): ?int
    {
        return $this->cctiId;
    }

    /**
     * @param int|null $cctiId
     */
    public function setCctiId(?int $cctiId): void
    {
        $this->cctiId = $cctiId;
    }

    /**
     * @return string|null
     */
    public function getCardAcceptorName(): ?string
    {
        return $this->cardAcceptorName;
    }

    /**
     * @param string|null $cardAcceptorName
     */
    public function setCardAcceptorName(?string $cardAcceptorName): void
    {
        $this->cardAcceptorName = $cardAcceptorName;
    }

    /**
     * @return string|null
     */
    public function getCardAcceptorCountry(): ?string
    {
        return $this->cardAcceptorCountry;
    }

    /**
     * @param string|null $cardAcceptorCountry
     */
    public function setCardAcceptorCountry(?string $cardAcceptorCountry): void
    {
        $this->cardAcceptorCountry = $cardAcceptorCountry;
    }

    /**
     * @return string|null
     */
    public function getCardAcceptorCity(): ?string
    {
        return $this->cardAcceptorCity;
    }

    /**
     * @param string|null $cardAcceptorCity
     */
    public function setCardAcceptorCity(?string $cardAcceptorCity): void
    {
        $this->cardAcceptorCity = $cardAcceptorCity;
    }

    /**
     * @return string|null
     */
    public function getOriginalResponseCode(): ?string
    {
        return $this->originalResponseCode;
    }

    /**
     * @param string|null $originalResponseCode
     */
    public function setOriginalResponseCode(?string $originalResponseCode): void
    {
        $this->originalResponseCode = $originalResponseCode;
    }

    /**
     * @return string|null
     */
    public function getAdditionalResponseData(): ?string
    {
        return $this->additionalResponseData;
    }

    /**
     * @param string|null $additionalResponseData
     */
    public function setAdditionalResponseData(?string $additionalResponseData): void
    {
        $this->additionalResponseData = $additionalResponseData;
    }

    /**
     * @return array|null
     */
    public function getAcquirerSpecificData(): ?array
    {
        return $this->acquirerSpecificData;
    }

    /**
     * @param array|null $acquirerSpecificData
     */
    public function setAcquirerSpecificData(?array $acquirerSpecificData): void
    {
        $this->acquirerSpecificData = $acquirerSpecificData;
    }

    /**
     * @return array|null
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * @param array|null $metadata
     */
    public function setMetadata(?array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @return string|null
     */
    public function getOrderReference(): ?string
    {
        return $this->orderReference;
    }

    /**
     * @param string|null $orderReference
     */
    public function setOrderReference(?string $orderReference): void
    {
        $this->orderReference = $orderReference;
    }

    /**
     * @return float|null
     */
    public function getSurchargeAmount(): ?float
    {
        return $this->surchargeAmount;
    }

    /**
     * @param float|null $surchargeAmount
     */
    public function setSurchargeAmount(?float $surchargeAmount): void
    {
        $this->surchargeAmount = $surchargeAmount;
    }

    /**
     * @return int|null
     */
    public function getPaymentMethod(): ?int
    {
        return $this->paymentMethod;
    }

    /**
     * @param int|null $paymentMethod
     */
    public function setPaymentMethod(?int $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }
}
