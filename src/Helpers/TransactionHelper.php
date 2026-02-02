<?php

namespace TheJano\QiCardPayment\Helpers;

use Illuminate\Support\Str;

class TransactionHelper
{
    public static function generateRequestId(): string
    {
        return Str::uuid()->toString();
    }

    public static function formatAmount(float $amount): float
    {
        return round($amount, 2);
    }
}
