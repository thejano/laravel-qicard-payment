<?php

namespace TheJano\QiCardPayment\Enums;

enum PaymentStatus: string
{
    case CREATED = 'CREATED';
    case FORM_SHOWED = 'FORM_SHOWED';
    case SUCCESS = 'SUCCESS';
    case FAILED = 'FAILED';
    case AUTHENTICATION_FAILED = 'AUTHENTICATION_FAILED';

    /**
     * Check if payment is in a final state
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::SUCCESS,
            self::FAILED,
            self::AUTHENTICATION_FAILED,
        ]);
    }

    /**
     * Check if payment was successful
     */
    public function isSuccessful(): bool
    {
        return $this === self::SUCCESS;
    }
}
