<?php

namespace TheJano\QiCardPayment\Contracts;

use TheJano\QiCardPayment\Data\QiCardPaymentResponse;
use TheJano\QiCardPayment\Data\QiCardRefundResponse;

interface PaymentGateway
{
    public function createPayment(string $requestId, float $amount, ?string $currency = null): QiCardPaymentResponse;

    public function getPaymentStatus(string $paymentId): QiCardPaymentResponse;

    public function cancelPayment(string $paymentId, string $requestId, float $amount): QiCardPaymentResponse;

    public function refundPayment(string $paymentId, string $requestId, float $amount): QiCardRefundResponse;
}
