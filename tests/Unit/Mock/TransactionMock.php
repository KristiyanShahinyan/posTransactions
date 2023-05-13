<?php

namespace App\Tests\Unit\Mock;

use App\Entity\Transaction;
use DateTime;

class TransactionMock
{
    public final static function realObject(): Transaction
    {
        $transaction = new Transaction();
        $transaction->setId(rand(0, 100));
        $transaction->setTransactionType(12);
        $transaction->setScaType(1);
        $transaction->setCurrency('GBP');
        $transaction->setInstance('Instance');
        $transaction->setAffiliate('Affiliate');
        $transaction->setAcquirerSpecificData(["replay_transaction" => "true"]);
        $transaction->setAdditionalResponseData('00 APPROVED');
        $transaction->setAmount(60.22);
        $transaction->setAppDetails(["appVersion" => 1,"sdkVersion" => 0]);
        $transaction->setCardPanObfuscated('527346XXXXXX2284');
        $transaction->setCardToken('cdae0900-3e3a-4bc0-b9e1-96270235231f');
        $transaction->setCardType('MASTERCARD');
        $transaction->setCctiId('81');
        $transaction->setChannel('mobile');
        $transaction->setCreateDate(new DateTime());
        $transaction->setErrorCode('00');
        $transaction->setDeviceId('1234567890123456');
        $transaction->setExecutionDate(new DateTime());
        $transaction->setIccData(["terminal_verification_result" => "41414141674145", "application_interchange_profile" => "475941"]);
        $transaction->setIsDeleted(false);
        $transaction->setIsHidden(false);
        $transaction->setMcc('12');
        $transaction->setMerchantIdent(uniqid());
        $transaction->setOriginalResponseCode('00');
        $transaction->setPaymentMethod(1);
        $transaction->setPosAcquiringInstitutionCode('1602160000');
        $transaction->setPosApplicationCryptogram('38D98A0B7030077B');
        $transaction->setPosApplicationId('A0000000041010');
        $transaction->setPosAuthCode('XXXXXX');
        $transaction->setPosCardAcceptorCity('Sofia');
        $transaction->setPosCardAcceptorCountry('BG');
        $transaction->setPosCardAcceptorIdentCode('606d6a0604');
        $transaction->setPosCardAcceptorName('test-acceptor');
        $transaction->setPosLocalDateTime(new DateTime());
        $transaction->setPosSystemTraceAuditNumber('000015');
        $transaction->setPosTransactionStamp('072000');
        $transaction->setReceivingInstitutionIdentificationCode('04825000000');
        $transaction->setReconciliationBatch('1');
        $transaction->setRefundableAmount(60.22);
        $transaction->setRemoteServiceTransaction('462wado5my');
        $transaction->setRetrievalReferenceNumber('6f11db67d4c10e2cf54ad870c8cd3902');
        $transaction->setStatus(1);
        $transaction->setStoreToken(uniqid());
        $transaction->setSurchargeAmount(1.11);
        $transaction->setTerminalId('16P19999');
        $transaction->setTerminalToken(uniqid());
        $transaction->setTimezoneName('UTC');
        $transaction->setTipAmount(1.11);
        $transaction->setTrnKey(uniqid());
        $transaction->setUserToken(uniqid());
        $transaction->setVoidable(true);

        return $transaction;
    }

    public final static function asArray(): array
    {
        return [
            'id' => rand(0, 100),
            'transaction_type' => 12,
            'sca_type' => 1,
            'currency' => 'GBP',
            'instance' => 'Instance',
            'affiliate' => 'Affiliate',
            'acquirer_specific_data' => ["replay_transaction" => "true"],
            'additional_response_data' => '00 APPROVED',
            'amount' => 60.22,
            'app_details' => ["appVersion" => 1,"sdkVersion" => 0],
            'card_pan_obfuscated' => '527346XXXXXX2284',
            'card_token' => 'cdae0900-3e3a-4bc0-b9e1-96270235231f',
            'card_type' => 'MASTERCARD',
            'cctId' => '81',
            'channel' => 'mobile',
            'create_date' => new DateTime(),
            'error_code' => '00',
            'device_id' => '1234567890123456',
            'execution_date' => new DateTime(),
            'icc_data' => ["terminal_verification_result" => "41414141674145", "application_interchange_profile" => "475941"],
            'is_deleted' => false,
            'is_hidden' => false,
            'mcc' => '12',
            'merchant_ident' => uniqid(),
            'original_response_code' => '00',
            'payment_method' => 1,
            'pos_acquiring_institution_code' => '1602160000',
            'pos_application_cryptogram' => '38D98A0B7030077B',
            'pos_application_id' => 'A0000000041010',
            'pos_auth_code' => 'XXXXXX',
            'pos_card_acceptor_city' => 'Sofia',
            'pos_card_acceptor_country' => 'BG',
            'pos_card_acceptor_ident_code' => '606d6a0604',
            'pos_card_acceptor_name' => 'test-acceptor',
            'pos_local_date_time' => new DateTime(),
            'pos_system_trace_audit_number' => '000015',
            'pos_transaction_stamp' => '072000',
            'receiving_institution_identification_code' => '04825000000',
            'reconciliation_batch' => '1',
            'refundable_amount' => 60.22,
            'remote_service_transaction' => '462wado5my',
            'retrieval_reference_number' => '6f11db67d4c10e2cf54ad870c8cd3902',
            'status' => 1,
            'store_token' => uniqid(),
            'surcharge_amount' => 1.11,
            'terminal_id' => '16P19999',
            'terminal_token' => uniqid(),
            'timezone_name' => 'UTC',
            'tip_amount' => 1.11,
            'trn_key' => uniqid(),
            'user_token' => uniqid(),
            'voidable' => true
        ];
    }
}