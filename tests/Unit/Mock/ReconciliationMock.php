<?php

namespace App\Tests\Unit\Mock;

use App\Entity\Reconciliation;
use DateTime;

class ReconciliationMock
{
    public static function realObject(): Reconciliation
    {
        $reconciliation = new Reconciliation();
        $reconciliation->setAffiliate('Affiliate');
        $reconciliation->setBatch(1);
        $reconciliation->setLastGenerated(new DateTime());
        $reconciliation->setCompress(false);
        $reconciliation->setReplacePan(false);
        $reconciliation->setAcquirer('Acquirer');
        $reconciliation->setBatchSize(1);
        $reconciliation->setOptions([]);

        return $reconciliation;
    }
}