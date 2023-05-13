<?php

namespace App\Entity;

use App\Constants\PaymentMethodAwareInterface;
use App\Constants\StatusAwareInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Phos\Entity\EntityInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @ORM\Table(name="t_transaction", schema="transactions")
 */
class Transaction implements EntityInterface
{
    const SCA_TYPE_REGULAR          = 1;
    const SCA_TYPE_PIN_PROTECTED    = 2;
    const SCA_TYPE_3DS_PROTECTED    = 3;

    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     *
     * @Groups({"index"})
     */
    protected $id;

    /**
     * Unique random string. 32 characters hex (16 bytes).
     *
     * @ORM\Column(name="trn_key", type="string", nullable=false)
     *
     * @Groups({"index"})
     */
    protected $trnKey;

    /**
     * Unique merchant identifier from client's system, with 4 letters prefix and dash.
     *
     * @ORM\Column(name="merchant_ident", type="string", length=50, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $merchantIdent;

    /**
     * Creation date/time, filled in automatically.
     *
     * @ORM\Column(name="create_date", type="datetime", nullable=false)
     *
     * @Groups({"create","index","update"})
     */
    protected $createDate;

    /**
     * Date of operation execution, e.g. bank transfers.
     *
     * @ORM\Column(name="execution_date", type="datetime", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $executionDate;

    /**
     * Unique identifier of user performing the operation.
     *
     * @ORM\Column(name="user_token", type="string", length=100, nullable=true)
     *
     * @Groups({"create","index","update"})
     *
     */
    protected $userToken;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string",nullable=true)
     *
     * @Groups({"create","update"})
     */
    protected $channel;

    /**
     * @var int|null
     *
     * 1 -> Card
     * 2 -> Paypal
     * 3 -> Venmo
     * 4 -> Cashapp
     *
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"index", "create","update"})
     */
    protected $paymentMethod;

    /**
     * Transaction amount.
     *
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=4, nullable=false)
     *
     * @Assert\NotBlank(groups={"create","update"})
     *
     * @Groups({"create","index","update"})
     */
    protected $amount;

    /**
     * Transaction currency.
     *
     * @ORM\Column(name="currency", type="string", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $currency;

    /**
     * Transaction tip amount.
     *
     * @ORM\Column(name="tip_amount", type="decimal", precision=10, scale=4, nullable=true)
     *
     * @Groups({"create","update", "index"})
     */
    protected $tipAmount;

    /**
     * Transaction type from the position of the sender.
     *
     * 1 - deposit
     * 2 - withdraw
     * 3 - send
     * 4 - request
     * 5 - payment
     * 6 - gps
     * 7 - bank deposit
     * 8 - receive
     * 9 - transfer
     * 11 - atm
     * 10 - pos
     * 12 - auth
     * 13 - order card
     * 14 - revert
     * 15 - fee
     * 16 - refund
     * 17 - void
     * 18 - nuapay
     * 19 - nuapay refund
     * 20 - pockyt sale
     * 21 - pockyt refund
     * 22 - pockyt void
     *
     * @ORM\Column(type="smallint", name="transaction_type", nullable=false)
     *
     * @Assert\NotBlank(groups={"create","update"})
     *
     * @Groups({"create","index","update"})
     */
    protected $transactionType;

    /**
     * List of items, in case of purchase of goods/services.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TransactionItem", mappedBy="transaction", fetch="EXTRA_LAZY", cascade={"persist", "remove"})
     */
    protected $transactionItems;

    /**
     * Transaction status
     * -2 - cancelled
     * -1 - failed
     * 0 - pending
     * 1 - successful.
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     *
     * @Groups({"create","index","update"})
     */
    protected $status;

    /**
     * Remote service transaction (e.g. alipay, wechat and etc.)
     *
     * @ORM\Column(name="remote_service_transaction", type="string", nullable=true)
     *
     * @Groups({"create","update", "index"})
     */
    protected $remoteServiceTransaction;

    /**
     * POS terminal ID where the transaction was authorized.
     *
     * @ORM\Column(name="terminal_id", type="string", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $terminalId;

    /**
     * Phos: Android device ID of the terminal.
     *
     * @Groups({"create","index","update"})
     *
     * @ORM\Column(name="device_id", type="string", nullable=true)
     */
    protected $deviceId;

    /**
     * Card token from the card service.
     *
     * @ORM\Column(name="card_token", type="string", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $cardToken;

    /**
     * Obfuscated PAN.
     *
     * @ORM\Column(name="card_pan_obfuscated", type="string", length=30,  nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $cardPanObfuscated;

    /**
     * Card brand - Visa, Mastercard, VPay, Maestro, etc.
     *
     * @ORM\Column(name="card_type", type="string", length=30,  nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $cardType;

    /**
     * CCTI ID - card type for Atos
     *
     * @ORM\Column(name="ccti_id", type="string", length=2,  nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $cctiId;

    /**
     * Merchant category code.
     *
     * @ORM\Column(name="mcc", type="string", nullable=true)
     *
     * @Groups({"create","index"})
     */
    protected $mcc;

    /**
     * Text representation of the merchant category code.
     *
     * @ORM\Column(name="mcc_description", type="string", nullable=true)
     */
    protected $mccDescription;

    /**
     * System trace audit number of the POS transaction (GICC field 11).
     *
     * @ORM\Column(name="system_trace_audit_number", type="string", length=50, nullable=true)
     *
     * @Groups({"create","index"})
     */
    protected $posSystemTraceAuditNumber;

    /**
     * Authorization code of the POS transaction (GICC field 38).
     *
     * @ORM\Column(name="pos_auth_code", type="string", length=50, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posAuthCode;

    /**
     * Chip-generated Application cryptogram of the POS transaction (tag 9F26).
     *
     * @ORM\Column(name="pos_application_cryptogram", type="string", length=50, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posApplicationCryptogram;

    /**
     * Application ID received from the chip in POS transaction (Dedicated file name, tag 84).
     *
     * @ORM\Column(name="pos_application_id", type="string", length=50, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posApplicationId;

    /**
     * Transaction stamp received by the host of the POS transaction (GICC field 61).
     *
     * @ORM\Column(name="pos_transaction_stamp", type="string", length=50, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posTransactionStamp;

    /**
     * Aquiring institution identification code of the POS transaction (GICC field 32).
     *
     * @ORM\Column(name="pos_acquiring_institution_code", type="string", length=30, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posAcquiringInstitutionCode;

    /**
     * Card acceptor identification code (Merchant ID = MID) (GICC field 42).
     *
     * @ORM\Column(name="pos_card_acceptor_ident_code", type="string", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posCardAcceptorIdentCode;

    /**
     * Card acceptor name (GICC field 43 part 1).
     *
     * @ORM\Column(name="pos_card_acceptor_name", type="string", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posCardAcceptorName;

    /**
     * Card acceptor city (GICC field 43 part 2).
     *
     * @ORM\Column(name="pos_card_acceptor_city", type="string", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posCardAcceptorCity;

    /**
     * Card acceptor country (GICC field 43 part 3).
     *
     * @ORM\Column(name="pos_card_acceptor_country", type="string", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posCardAcceptorCountry;

    /**
     * Sequence number (GICC field 57 subfield 1).
     *
     * @ORM\Column(name="pos_sequence_number", type="string", length=50, nullable=true)
     *
     * @Groups({"create","update"})
     */
    protected $posSequenceNumber;

    /**
     * Generation number (GICC field 57 subfield 2).
     *
     * @ORM\Column(name="pos_generation_number", type="string", length=50, nullable=true)
     *
     * @Groups({"create","update"})
     */
    protected $posGenerationNumber;

    /**
     * POS terminal local date/time (GICC fields 12 and 13).
     *
     * @ORM\Column(name="pos_local_date_time", type="datetime", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $posLocalDateTime;

    /**
     * Soft-delete flag.
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=false, options={"default":false})
     */
    protected $isDeleted;

    /**
     * TODO.
     *
     * @ORM\Column(name="is_hidden", type="boolean", nullable=true, options={"default":false})
     */
    protected $isHidden;

    /**
     * Original sale transaction, to which void and/or refund is linked
     * 
     * @ORM\ManyToOne(targetEntity="Transaction")
     * @ORM\JoinColumn(name="linked_transaction", nullable=true, referencedColumnName="id")
     *
     * @Groups({"create","index","update"})
     */
    protected $linkedTransaction;

    /**
     * Total amount that is available to be refunded on this transaction.
     *
     * @ORM\Column(name="refundable_amount", type="decimal", precision=10, scale=4, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $refundableAmount;

    /**
     * Is it possible to void the transaction?
     *
     * @ORM\Column(name="voidable", type="boolean", nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $voidable;

    /**
     * Client IP address.
     *
     * @ORM\Column(name="client_ip", type="string", length=60, nullable=true)
     */
    protected $clientIp;

    /**
     * Client country.
     *
     * @ORM\Column(name="client_country", type="string", length=80, nullable=true)
     */
    protected $clientCountry;

    /**
     * External error code from POS authorization.
     *
     * @ORM\Column(name="error_code", type="string", length=30, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected $errorCode;


    /**
     * Localization of the transaction
     * @var string|null
     * @ORM\Column(name="longitude", type="string", length=30, nullable=true)
     * @Groups({"create","index","update"})
     */
    protected ?string $longitude = null;


    /**
     * Localization of the transaction
     * @ORM\Column(name="latitude", type="string", length=30, nullable=true)
     * @Groups({"create","index","update"})
     */
    protected ?string $latitude = null;

    /**
     * @ORM\Column(name="timezone_name", type="string", length=255)
     * @Groups({"create","index","update"})
     */
    protected string $timezoneName = 'UTC';

    /**
     * @ORM\Column(name="processor", type="string", length=255, nullable=true)
     * @Groups({"create","index","update"})
     */
    protected ?string $processor = null;

    /**
     * @ORM\Column(name="retrieval_reference_number", type="string", length=255, nullable=true)
     * @Groups({"create","index","update"})
     */
    protected ?string $retrievalReferenceNumber = null;

    /**
     * @ORM\Column(name="sca_type", type="integer", options={"default":1})
     * @Groups({"create","index","update"})
     */
    protected int $scaType = self::SCA_TYPE_REGULAR;
    
    /**
     * @ORM\Column(name="metadata", type="json", nullable=true)
     * @Groups({"create","index","update"})
     */
    protected ?array $metadata = null;

    /**
     * @ORM\Column(name="receiving_institution_identification_code", type="string", length=255, nullable=true)
     * @Groups({"create","index","update"})
     */
    protected ?string $receivingInstitutionIdentificationCode = null;

    /**
     *
     * @ORM\Column(name="original_response_code", type="string", length=30, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected ?string $originalResponseCode = null;

    /**
     *
     * @ORM\Column(name="additional_response_data", type="string", length=255, nullable=true)
     *
     * @Groups({"create","index","update"})
     */
    protected ?string $additionalResponseData = null;

    /**
     * @ORM\Column(name="acquirer_specific_data", type="json", nullable=true)
     * @Groups({"create","index","update"})
     */
    protected ?array $acquirerSpecificData = null;

    /**
     * @ORM\Column(name="affiliate", type="string", nullable=true)
     * @Groups({"create","index","update"})
     */
    protected ?string $affiliate = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"create","index","update"})
     */
    private ?string $terminalToken = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"create","index"})
     */
    private ?string $storeToken = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"create","index"})
     */
    private ?string $instance = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"create","index"})
     */
    private ?string $orderReference = null;

    /**
     * @ORM\Column(name="icc_data", type="json", nullable=true)
     * @Groups({"index","update"})
     */
    protected ?array $iccData = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $reconciliationBatch = null;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"create","index"})
     */
    private ?array $appDetails = null;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=4, nullable=true, options={"default": null})
     * @Groups({"create","index","update"})
     */
    private $surchargeAmount = null;

    /**
     * Transaction constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->paymentMethod = PaymentMethodAwareInterface::CARD;
        $this->createDate = new DateTime();
        $this->isDeleted = false;
        $this->isHidden = false;
        $this->status = StatusAwareInterface::PENDING;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTrnKey()
    {
        return $this->trnKey;
    }

    /**
     * @param mixed $trnKey
     */
    public function setTrnKey($trnKey): void
    {
        $this->trnKey = $trnKey;
    }

    /**
     * @return mixed
     */
    public function getMerchantIdent()
    {
        return $this->merchantIdent;
    }

    /**
     * @param mixed $merchantIdent
     */
    public function setMerchantIdent($merchantIdent): void
    {
        $this->merchantIdent = $merchantIdent;
    }

    public function getCreateDate(): DateTime
    {
        return $this->createDate;
    }

    public function setCreateDate(DateTime $createDate): void
    {
        $this->createDate = $createDate;
    }

    /**
     * @return mixed
     */
    public function getExecutionDate()
    {
        return $this->executionDate;
    }

    /**
     * @param mixed $executionDate
     */
    public function setExecutionDate($executionDate): void
    {
        $this->executionDate = $executionDate;
    }

    /**
     * @return mixed
     */
    public function getUserToken()
    {
        return $this->userToken;
    }

    /**
     * @param mixed $userToken
     */
    public function setUserToken($userToken): void
    {
        $this->userToken = $userToken;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param mixed $transactionType
     */
    public function setTransactionType($transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return mixed
     */
    public function getTransactionItems()
    {
        return $this->transactionItems;
    }

    /**
     * @param mixed $transactionItems
     */
    public function setTransactionItems($transactionItems): void
    {
        $this->transactionItems = $transactionItems;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
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
    public function getCardToken()
    {
        return $this->cardToken;
    }

    /**
     * @param mixed $cardToken
     */
    public function setCardToken($cardToken): void
    {
        $this->cardToken = $cardToken;
    }

    /**
     * @return mixed
     */
    public function getCardPanObfuscated()
    {
        return $this->cardPanObfuscated;
    }

    /**
     * @param mixed $cardPanObfuscated
     */
    public function setCardPanObfuscated($cardPanObfuscated): void
    {
        $this->cardPanObfuscated = $cardPanObfuscated;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @param mixed $cardType
     */
    public function setCardType($cardType): void
    {
        $this->cardType = $cardType;
    }

    /**
     * @return mixed
     */
    public function getCctiId()
    {
        return $this->cctiId;
    }

    /**
     * @param mixed $cctiId
     */
    public function setCctiId($cctiId): void
    {
        $this->cctiId = $cctiId;
    }

    /**
     * @return mixed
     */
    public function getMcc()
    {
        return $this->mcc;
    }

    /**
     * @param mixed $mcc
     */
    public function setMcc($mcc): void
    {
        $this->mcc = $mcc;
    }

    /**
     * @return mixed
     */
    public function getMccDescription()
    {
        return $this->mccDescription;
    }

    /**
     * @param mixed $mccDescription
     */
    public function setMccDescription($mccDescription): void
    {
        $this->mccDescription = $mccDescription;
    }

    /**
     * @return mixed
     */
    public function getPosSystemTraceAuditNumber()
    {
        return $this->posSystemTraceAuditNumber;
    }

    /**
     * @param mixed $posSystemTraceAuditNumber
     */
    public function setPosSystemTraceAuditNumber($posSystemTraceAuditNumber): void
    {
        $this->posSystemTraceAuditNumber = $posSystemTraceAuditNumber;
    }

    /**
     * @return mixed
     */
    public function getPosAuthCode()
    {
        return $this->posAuthCode;
    }

    /**
     * @param mixed $posAuthCode
     */
    public function setPosAuthCode($posAuthCode): void
    {
        $this->posAuthCode = $posAuthCode;
    }

    /**
     * @return mixed
     */
    public function getPosApplicationCryptogram()
    {
        return $this->posApplicationCryptogram;
    }

    /**
     * @param mixed $posApplicationCryptogram
     */
    public function setPosApplicationCryptogram($posApplicationCryptogram): void
    {
        $this->posApplicationCryptogram = $posApplicationCryptogram;
    }

    /**
     * @return mixed
     */
    public function getPosApplicationId()
    {
        return $this->posApplicationId;
    }

    /**
     * @param mixed $posApplicationId
     */
    public function setPosApplicationId($posApplicationId): void
    {
        $this->posApplicationId = $posApplicationId;
    }

    /**
     * @return mixed
     */
    public function getPosTransactionStamp()
    {
        return $this->posTransactionStamp;
    }

    /**
     * @param mixed $posTransactionStamp
     */
    public function setPosTransactionStamp($posTransactionStamp): void
    {
        $this->posTransactionStamp = $posTransactionStamp;
    }

    /**
     * @return mixed
     */
    public function getPosAcquiringInstitutionCode()
    {
        return $this->posAcquiringInstitutionCode;
    }

    /**
     * @param mixed $posAcquiringInstitutionCode
     */
    public function setPosAcquiringInstitutionCode($posAcquiringInstitutionCode): void
    {
        $this->posAcquiringInstitutionCode = $posAcquiringInstitutionCode;
    }

    /**
     * @return mixed
     */
    public function getPosCardAcceptorIdentCode()
    {
        return $this->posCardAcceptorIdentCode;
    }

    /**
     * @param mixed $posCardAcceptorIdentCode
     */
    public function setPosCardAcceptorIdentCode($posCardAcceptorIdentCode): void
    {
        $this->posCardAcceptorIdentCode = $posCardAcceptorIdentCode;
    }

    /**
     * @return mixed
     */
    public function getPosCardAcceptorName()
    {
        return $this->posCardAcceptorName;
    }

    /**
     * @param mixed $posCardAcceptorName
     */
    public function setPosCardAcceptorName($posCardAcceptorName): void
    {
        $this->posCardAcceptorName = $posCardAcceptorName;
    }

    /**
     * @return mixed
     */
    public function getPosCardAcceptorCity()
    {
        return $this->posCardAcceptorCity;
    }

    /**
     * @param mixed $posCardAcceptorCity
     */
    public function setPosCardAcceptorCity($posCardAcceptorCity): void
    {
        $this->posCardAcceptorCity = $posCardAcceptorCity;
    }

    /**
     * @return mixed
     */
    public function getPosCardAcceptorCountry()
    {
        return $this->posCardAcceptorCountry;
    }

    /**
     * @param mixed $posCardAcceptorCountry
     */
    public function setPosCardAcceptorCountry($posCardAcceptorCountry): void
    {
        $this->posCardAcceptorCountry = $posCardAcceptorCountry;
    }

    /**
     * @return mixed
     */
    public function getPosSequenceNumber()
    {
        return $this->posSequenceNumber;
    }

    /**
     * @param mixed $posSequenceNumber
     */
    public function setPosSequenceNumber($posSequenceNumber): void
    {
        $this->posSequenceNumber = $posSequenceNumber;
    }

    /**
     * @return mixed
     */
    public function getPosGenerationNumber()
    {
        return $this->posGenerationNumber;
    }

    /**
     * @param mixed $posGenerationNumber
     */
    public function setPosGenerationNumber($posGenerationNumber): void
    {
        $this->posGenerationNumber = $posGenerationNumber;
    }

    /**
     * @return mixed
     */
    public function getPosLocalDateTime()
    {
        return $this->posLocalDateTime;
    }

    /**
     * @param mixed $posLocalDateTime
     */
    public function setPosLocalDateTime($posLocalDateTime): void
    {
        $this->posLocalDateTime = $posLocalDateTime;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function isHidden(): bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $isHidden): void
    {
        $this->isHidden = $isHidden;
    }

    /**
     * @return mixed
     */
    public function getLinkedTransaction()
    {
        return $this->linkedTransaction;
    }

    /**
     * @param mixed $linkedTransaction
     */
    public function setLinkedTransaction($linkedTransaction): void
    {
        $this->linkedTransaction = $linkedTransaction;
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
    public function getClientIp()
    {
        return $this->clientIp;
    }

    /**
     * @param mixed $clientIp
     */
    public function setClientIp($clientIp): void
    {
        $this->clientIp = $clientIp;
    }

    /**
     * @return mixed
     */
    public function getClientCountry()
    {
        return $this->clientCountry;
    }

    /**
     * @param mixed $clientCountry
     */
    public function setClientCountry($clientCountry): void
    {
        $this->clientCountry = $clientCountry;
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
    public function getTipAmount()
    {
        return $this->tipAmount;
    }

    /**
     * @param mixed $tipAmount
     */
    public function setTipAmount($tipAmount): void
    {
        $this->tipAmount = $tipAmount;
    }

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * @param string|null $channel
     */
    public function setChannel($channel): void
    {
        $this->channel = $channel;
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

    public function getTimezoneName(): string
    {
        return $this->timezoneName;
    }

    public function setTimezoneName(string $timezoneName): void
    {
        $this->timezoneName = $timezoneName;
    }

    public function getProcessor(): ?string
    {
        return $this->processor;
    }
    
    public function setProcessor(?string $processor): void
    {
        $this->processor = $processor;
    }

    /**
     * @return string
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

    public function getScaType(): int
    {
        return $this->scaType;
    }

    public function setScaType(int $scaType): void
    {
        $this->scaType = $scaType;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): void
    {
        $this->metadata = $metadata;
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
     * @return string|null
     */
    public function getAffiliate(): ?string
    {
        return $this->affiliate;
    }

    /**
     * @param string|null $affiliate
     */
    public function setAffiliate(?string $affiliate): void
    {
        $this->affiliate = $affiliate;
    }

    public function getTerminalToken(): ?string
    {
        return $this->terminalToken;
    }

    public function setTerminalToken(?string $terminalToken): void
    {
        $this->terminalToken = $terminalToken;
    }

    /**
     * @return string|null
     */
    public function getStoreToken(): ?string
    {
        return $this->storeToken;
    }

    /**
     * @param string|null $storeToken
     */
    public function setStoreToken(?string $storeToken): void
    {
        $this->storeToken = $storeToken;
    }

    public function getInstance(): ?string
    {
        return $this->instance;
    }

    public function setInstance(?string $instance): void
    {
        $this->instance = $instance;
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
     * @return array|null
     */
    public function getIccData(): ?array
    {
        return $this->iccData;
    }

    /**
     * @param array|null $iccData
     */
    public function setIccData(?array $iccData): void
    {
        $this->iccData = $iccData;
    }

    public function getReconciliationBatch(): ?string
    {
        return $this->reconciliationBatch;
    }

    public function setReconciliationBatch(?string $reconciliationBatch): void
    {
        $this->reconciliationBatch = $reconciliationBatch;
    }

    public function getAppDetails(): ?array
    {
        return $this->appDetails;
    }

    public function setAppDetails(?array $appDetails): void
    {
        $this->appDetails = $appDetails;
    }

    /**
     * @return mixed
     */
    public function getSurchargeAmount()
    {
        return $this->surchargeAmount;
    }

    /**
     * @param mixed $surchargeAmount
     */
    public function setSurchargeAmount($surchargeAmount): void
    {
        $this->surchargeAmount = $surchargeAmount;
    }
}
