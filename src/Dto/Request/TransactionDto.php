<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TransactionDto.
 */
class TransactionDto implements DtoInterface
{
    /**
     * @var DateTime|null
     * @Assert\Type("DateTime")
     */
    protected $transactionDate;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $merchantIdent;

    /**
     * @var int|null
     * @Assert\Type("integer")
     */
    protected $transactionType;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $userToken;

    /**
     * @var string|null
     */
    protected $channel;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $cardToken;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $cardPan;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $cardType;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $recipientEmail;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $recipientPhone;

    /**
     * @var float|null
     * @Assert\Type("float")
     */
    protected $amount;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $currency;

    /**
     * @var float|null
     * @Assert\Type("float")
     */
    protected $tipAmount;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $transactionId;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $reference;

    /**
     * @var int|null
     * @Assert\Type("int")
     */
    protected $status;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $clientInfo;

    /**
     * @var DateTime|null
     * @Assert\Type("DateTime")
     */
    protected $clientTime;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $errorCode;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $service;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $acquiringInstitutionCode;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $systemTraceAuditNumber;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $authCode;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $captureReference;

    /**
     * @var DateTime|null
     * @Assert\Type("DateTime")
     */
    protected $posLocalDateTime;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $posEntryMode;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $posProcessingCode;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $cardSequenceNumber;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $track2Data;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $posTerminalIdCode;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $cardAcceptorIdentCode;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $cardAcceptorLocation;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $cctiId;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $remoteServiceTransaction;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $sequenceNumber;

    /**
     * @var string|null
     * @Assert\Type("string")
     */
    protected $generationNumber;

    /**
     * @var TransactionIccDto|null
     * @Assert\Valid()
     */
    protected $iccData;

    /**
     * @var TransactionAdditionalDataDto|null
     * @Assert\Valid()
     */
    protected $additionalData;

    /**
     * @var string|null
     */
    protected $deviceId;

    /**
     */
    protected $applicationId;

    /**
     */
    protected $applicationCryptogram;

    /**
     */
    protected $cardAcceptorName;

    /**
     */
    protected $cardAcceptorCity;

    /**
     */
    protected $cardAcceptorCountry;

    /**
     */
    protected $transactionStamp;

    /**
     * @var TransactionDto|null
     */
    protected $linkedTransaction;
    
    /**
     * @var string|null
     */
    protected $processor;
    
    /**
     * @var string|null
     */
    protected $retrievalReferenceNumber;

    public function getTransactionDate(): ?DateTime
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(?DateTime $transactionDate): void
    {
        $this->transactionDate = $transactionDate;
    }

    public function getMerchantIdent(): ?string
    {
        return $this->merchantIdent;
    }

    public function setMerchantIdent(?string $merchantIdent): void
    {
        $this->merchantIdent = $merchantIdent;
    }

    public function getTransactionType(): ?int
    {
        return $this->transactionType;
    }

    public function setTransactionType(?int $transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    public function getUserToken(): ?string
    {
        return $this->userToken;
    }

    public function setUserToken(?string $userToken): void
    {
        $this->userToken = $userToken;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    public function getCardToken(): ?string
    {
        return $this->cardToken;
    }

    public function setCardToken(?string $cardToken): void
    {
        $this->cardToken = $cardToken;
    }

    public function getCardPan(): ?string
    {
        return $this->cardPan;
    }

    public function setCardPan(?string $cardPan): void
    {
        $this->cardPan = $cardPan;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function setCardType(?string $cardType): void
    {
        $this->cardType = $cardType;
    }

    public function getRecipientEmail(): ?string
    {
        return $this->recipientEmail;
    }

    public function setRecipientEmail(?string $recipientEmail): void
    {
        $this->recipientEmail = $recipientEmail;
    }

    public function getRecipientPhone(): ?string
    {
        return $this->recipientPhone;
    }

    public function setRecipientPhone(?string $recipientPhone): void
    {
        $this->recipientPhone = $recipientPhone;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getTipAmount(): ?float
    {
        return $this->tipAmount;
    }

    public function setTipAmount(?float $tipAmount): void
    {
        $this->tipAmount = $tipAmount;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(?string $transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    public function getClientInfo(): ?string
    {
        return $this->clientInfo;
    }

    public function setClientInfo(?string $clientInfo): void
    {
        $this->clientInfo = $clientInfo;
    }

    public function getClientTime(): ?DateTime
    {
        return $this->clientTime;
    }

    public function setClientTime(?DateTime $clientTime): void
    {
        $this->clientTime = $clientTime;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function setErrorCode(?string $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): void
    {
        $this->service = $service;
    }

    public function getAcquiringInstitutionCode(): ?string
    {
        return $this->acquiringInstitutionCode;
    }

    public function setAcquiringInstitutionCode(?string $acquiringInstitutionCode): void
    {
        $this->acquiringInstitutionCode = $acquiringInstitutionCode;
    }

    public function getSystemTraceAuditNumber(): ?string
    {
        return $this->systemTraceAuditNumber;
    }

    public function setSystemTraceAuditNumber(?string $systemTraceAuditNumber): void
    {
        $this->systemTraceAuditNumber = $systemTraceAuditNumber;
    }

    public function getAuthCode(): ?string
    {
        return $this->authCode;
    }

    public function setAuthCode(?string $authCode): void
    {
        $this->authCode = $authCode;
    }

    public function getCaptureReference(): ?string
    {
        return $this->captureReference;
    }

    public function setCaptureReference(?string $captureReference): void
    {
        $this->captureReference = $captureReference;
    }

    public function getPosLocalDateTime(): ?DateTime
    {
        return $this->posLocalDateTime;
    }

    public function setPosLocalDateTime(?DateTime $posLocalDateTime): void
    {
        $this->posLocalDateTime = $posLocalDateTime;
    }

    public function getPosEntryMode(): ?string
    {
        return $this->posEntryMode;
    }

    public function setPosEntryMode(?string $posEntryMode): void
    {
        $this->posEntryMode = $posEntryMode;
    }

    public function getPosProcessingCode(): ?string
    {
        return $this->posProcessingCode;
    }

    public function setPosProcessingCode(?string $posProcessingCode): void
    {
        $this->posProcessingCode = $posProcessingCode;
    }

    public function getCardSequenceNumber(): ?string
    {
        return $this->cardSequenceNumber;
    }

    public function setCardSequenceNumber(?string $cardSequenceNumber): void
    {
        $this->cardSequenceNumber = $cardSequenceNumber;
    }

    public function getTrack2Data(): ?string
    {
        return $this->track2Data;
    }

    public function setTrack2Data(?string $track2Data): void
    {
        $this->track2Data = $track2Data;
    }

    public function getPosTerminalIdCode(): ?string
    {
        return $this->posTerminalIdCode;
    }

    public function setPosTerminalIdCode(?string $posTerminalIdCode): void
    {
        $this->posTerminalIdCode = $posTerminalIdCode;
    }

    public function getCardAcceptorIdentCode(): ?string
    {
        return $this->cardAcceptorIdentCode;
    }

    public function setCardAcceptorIdentCode(?string $cardAcceptorIdentCode): void
    {
        $this->cardAcceptorIdentCode = $cardAcceptorIdentCode;
    }

    public function getCardAcceptorLocation(): ?string
    {
        return $this->cardAcceptorLocation;
    }

    public function setCardAcceptorLocation(?string $cardAcceptorLocation): void
    {
        $this->cardAcceptorLocation = $cardAcceptorLocation;
    }

    public function getCctiId(): ?string
    {
        return $this->cctiId;
    }

    public function setCctiId(?string $cctiId): void
    {
        $this->cctiId = $cctiId;
    }

    public function getRemoteServiceTransaction(): ?string
    {
        return $this->remoteServiceTransaction;
    }

    public function setRemoteServiceTransaction(?string $remoteServiceTransaction): void
    {
        $this->remoteServiceTransaction = $remoteServiceTransaction;
    }

    public function getIccData(): ?TransactionIccDto
    {
        return $this->iccData;
    }

    public function setIccData(?TransactionIccDto $iccData): void
    {
        $this->iccData = $iccData;
    }

    public function getAdditionalData(): ?TransactionAdditionalDataDto
    {
        return $this->additionalData;
    }

    public function setAdditionalData(?TransactionAdditionalDataDto $additionalData): void
    {
        $this->additionalData = $additionalData;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(?string $deviceId): void
    {
        $this->deviceId = $deviceId;
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
    public function getCardAcceptorName()
    {
        return $this->cardAcceptorName;
    }

    /**
     * @param mixed $cardAcceptorName
     */
    public function setCardAcceptorName($cardAcceptorName): void
    {
        $this->cardAcceptorName = $cardAcceptorName;
    }

    /**
     * @return mixed
     */
    public function getCardAcceptorCity()
    {
        return $this->cardAcceptorCity;
    }

    /**
     * @param mixed $cardAcceptorCity
     */
    public function setCardAcceptorCity($cardAcceptorCity): void
    {
        $this->cardAcceptorCity = $cardAcceptorCity;
    }

    /**
     * @return mixed
     */
    public function getCardAcceptorCountry()
    {
        return $this->cardAcceptorCountry;
    }

    /**
     * @param mixed $cardAcceptorCountry
     */
    public function setCardAcceptorCountry($cardAcceptorCountry): void
    {
        $this->cardAcceptorCountry = $cardAcceptorCountry;
    }

    public function getSequenceNumber(): ?string
    {
        return $this->sequenceNumber;
    }

    public function setSequenceNumber(?string $sequenceNumber): void
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    public function getGenerationNumber(): ?string
    {
        return $this->generationNumber;
    }

    public function setGenerationNumber(?string $generationNumber): void
    {
        $this->generationNumber = $generationNumber;
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
     * @return TransactionDto|null
     */
    public function getLinkedTransaction()
    {
        return $this->linkedTransaction;
    }
    
    /**
     * @param TransactionDto|null $linkedTransaction
     */
    public function setLinkedTransaction($linkedTransaction): void
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
}
