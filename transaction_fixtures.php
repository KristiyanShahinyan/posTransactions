#!/usr/bin/env php
<?php

use App\Constants\StatusAwareInterface;
use App\Constants\TransactionTypes;
use App\Entity\Transaction;

require __DIR__ . '/config/bootstrap.php';

$kernel = new \App\Kernel('dev', true);
$kernel->boot();

$em = $kernel->getContainer()->get('doctrine.orm.entity_manager');




for ($i = 0; $i <= 10; $i++) {
    for ($h=0; $h< 24; $h++) {

        echo "$i : $h \n";
        $count = $h > 9 && $h < 22 ? 8 : 2;
        for ($c=1; $c <= $count; $c++) {
            $date = new DateTime(sprintf('-%d day %d:%d:%d', $i, $h, mt_rand(0,59), mt_rand(0, 59)));

            if ($date->format('D') === 'Thu' && (int)$date->format('d') <= 15) {
                continue;
            }

            $transaction = new Transaction();


            $transaction->setAmount(3.45);
            $transaction->setCurrency('BGN');
            $transaction->setStatus(StatusAwareInterface::SUCCESSFUL);
            $transaction->setTransactionType(TransactionTypes::AUTH);
            $transaction->setTrnKey(bin2hex(random_bytes(16)));
            $transaction->setCreateDate((new DateTime())->setTime(0,0,0));
            $transaction->setCardPanObfuscated('1234XXXXXXXX1234');
            $transaction->setPosAuthCode('XXXXXXX');
            $transaction->setPosLocalDateTime(new DateTime());
            $transaction->setPosSystemTraceAuditNumber('xxxxxxx');

            $transaction->setMerchantIdent('38bfac5ecff6c2995adef6d6df8a9d3eb');
            $transaction->setDeviceId('1234123412341233');
            $transaction->setTerminalId('16PH0029');
            $transaction->setCardType('VISA');

            $transaction->setUserToken('de8a01ee57e992442563c7b3a130c98e');

            $em->persist($transaction);
        }

        $em->flush();
        $em->clear();

        gc_collect_cycles();
    }
}

