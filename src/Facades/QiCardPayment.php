<?php

namespace TheJano\QiCardPayment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \TheJano\QiCardPayment\Data\QiCardPaymentResponse createPayment(string $requestId, float $amount, ?string $currency = null, ?\TheJano\QiCardPayment\Data\CustomerInfo $customerInfo = null, ?\TheJano\QiCardPayment\Data\BrowserInfo $browserInfo = null, ?array $additionalInfo = null, bool $appChannel = false)
 * @method static \TheJano\QiCardPayment\Data\QiCardPaymentResponse getPaymentStatus(string $paymentId)
 * @method static \TheJano\QiCardPayment\Data\QiCardPaymentResponse cancelPayment(string $paymentId, string $requestId, float $amount)
 * @method static \TheJano\QiCardPayment\Data\QiCardRefundResponse refundPayment(string $paymentId, string $requestId, float $amount)
 * @method static bool verifyWebhookSignature(array $payload, string $signature)
 *
 * @see \TheJano\QiCardPayment\Services\QiCardPayment
 */
class QiCardPayment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TheJano\QiCardPayment\Services\QiCardPayment::class;
    }
}
