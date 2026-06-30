<?php

namespace TheJano\QiCardPayment\Contracts;

use TheJano\QiCardPayment\Data\BrowserInfo;
use TheJano\QiCardPayment\Data\CustomerInfo;
use TheJano\QiCardPayment\Data\QiCardPaymentResponse;
use TheJano\QiCardPayment\Data\QiCardRefundResponse;

interface PaymentGateway
{
    public function createPayment(
        string $requestId,
        float $amount,
        ?string $currency = null,
        ?CustomerInfo $customerInfo = null,
        ?BrowserInfo $browserInfo = null,
        ?array $additionalInfo = null,
        bool $appChannel = false
    ): QiCardPaymentResponse;

    public function getPaymentStatus(string $paymentId): QiCardPaymentResponse;

    public function cancelPayment(string $paymentId, string $requestId, float $amount): QiCardPaymentResponse;

    public function refundPayment(string $paymentId, string $requestId, float $amount): QiCardRefundResponse;

    public function verifyWebhookSignature(array $payload, string $signature): bool;
}
