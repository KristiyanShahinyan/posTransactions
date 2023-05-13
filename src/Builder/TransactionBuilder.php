<?php

namespace App\Builder;

use App\Dto\Response\TransactionDto;
use App\Dto\Response\TransactionListDto;
use App\Entity\Transaction;
use Traversable;

/**
 * Class TransactionBuilder.
 */
class TransactionBuilder
{
    public function buildTransaction(Transaction $transaction): TransactionDto
    {
        $dto = new TransactionDto();

        $dto->setId($transaction->getId());
        $dto->setTrnKey($transaction->getTrnKey());
        $dto->setAmount($transaction->getAmount());
        $dto->setCurrency($transaction->getCurrency());
        $dto->setUser($transaction->getUserToken());
        $dto->setCard($transaction->getCardToken());
        $dto->setCardPan($transaction->getCardPanObfuscated());
        $dto->setCardType($transaction->getCardType());
        $dto->setTransactionType($transaction->getTransactionType());
        $dto->setMcc($transaction->getMcc());
        $dto->setMccDescription($transaction->getMccDescription());
        $dto->setStatus($transaction->getStatus());
        $dto->setTrnDate($transaction->getCreateDate());
        $dto->setRemoteServiceTransaction($transaction->getRemoteServiceTransaction());
        $dto->setStan($transaction->getPosSystemTraceAuditNumber());
        $dto->setAuthCode($transaction->getPosAuthCode());
        $dto->setTerminalId($transaction->getTerminalId());
        $dto->setPosLocalDateTime($transaction->getPosLocalDateTime());
        $dto->setDeviceId($transaction->getDeviceId());
        $dto->setTipAmount($transaction->getTipAmount());
        $dto->setErrorCode($transaction->getErrorCode());
        $dto->setApplicationId($transaction->getPosApplicationId());
        $dto->setApplicationCryptogram($transaction->getPosApplicationCryptogram());
        $dto->setCardAcceptorIdentCode($transaction->getPosCardAcceptorIdentCode());
        $dto->setTransactionStamp($transaction->getPosTransactionStamp());
        $dto->setRefundableAmount($transaction->getRefundableAmount());
        $dto->setVoidable($transaction->getVoidable());
        $dto->setChannel($transaction->getChannel());
        $dto->setMerchant($transaction->getMerchantIdent());
        $dto->setLongitude($transaction->getLongitude());
        $dto->setLatitude($transaction->getLatitude());
        $dto->setLinkedTransaction($transaction->getLinkedTransaction());
        $dto->setProcessor($transaction->getProcessor());
        $dto->setRetrievalReferenceNumber($transaction->getRetrievalReferenceNumber());
        $dto->setScaType($transaction->getScaType());
        $dto->setReceivingInstitutionIdentificationCode($transaction->getReceivingInstitutionIdentificationCode());
        $dto->setAcquirerCode($transaction->getPosAcquiringInstitutionCode());
        $dto->setCctiId($transaction->getCctiId());
        $dto->setCardAcceptorName($transaction->getPosCardAcceptorName());
        $dto->setCardAcceptorCountry($transaction->getPosCardAcceptorCountry());
        $dto->setCardAcceptorCity($transaction->getPosCardAcceptorCity());
        $dto->setAdditionalResponseData($transaction->getAdditionalResponseData());
        $dto->setAcquirerSpecificData($transaction->getAcquirerSpecificData());
        $dto->setMetadata($transaction->getMetadata());
        $dto->setOrderReference($transaction->getOrderReference());
        $dto->setSurchargeAmount($transaction->getSurchargeAmount());
        $dto->setOriginalResponseCode($transaction->getOriginalResponseCode());
        $dto->setPaymentMethod($transaction->getPaymentMethod());

        return $dto;
    }

    /**
     * @param array|Traversable $transactions
     * @return TransactionListDto
     */
    public function buildTransactionList($transactions): TransactionListDto
    {
        $dto = new TransactionListDto();
        $items = [];

        foreach ($transactions as $transaction) {
            $items[] = $this->buildTransaction($transaction);
        }

        $dto->setItems($items);

        return $dto;
    }
}
