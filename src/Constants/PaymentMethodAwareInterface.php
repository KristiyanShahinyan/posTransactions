<?php

namespace App\Constants;

interface PaymentMethodAwareInterface
{
    public const CARD   = 1;
    public const ALIPAY = 2;
    public const WECHAT = 3;
}
