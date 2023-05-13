<?php

namespace App\Constants;

interface StatusAwareInterface
{
    public const CANCELLED  = -2;
    public const FAILED     = -1;
    public const PENDING    = 0;
    public const SUCCESSFUL = 1;
}
