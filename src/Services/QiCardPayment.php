<?php

namespace TheJano\QiCardPayment\Services;

use Illuminate\Support\Facades\Http;
use TheJano\QiCardPayment\Contracts\PaymentGateway;
use TheJano\QiCardPayment\Data\BrowserInfo;
use TheJano\QiCardPayment\Data\CustomerInfo;
use TheJano\QiCardPayment\Data\QiCardPaymentResponse;
use TheJano\QiCardPayment\Data\QiCardRefundResponse;

class QiCardPayment implements PaymentGateway
{
    private static ?QiCardPayment $instance = null;

    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected string $terminalId;

    private function __construct()
    {
        $config = config('qicard');
        $this->baseUrl = rtrim($config['base_url'], '/');
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->terminalId = $config['terminal_id'];
    }

    public static function make(): QiCardPayment
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function createPayment(
        string $requestId,
        float $amount,
        ?string $currency = null,
        ?CustomerInfo $customerInfo = null,
        ?BrowserInfo $browserInfo = null,
        ?array $additionalInfo = null,
        bool $appChannel = false
    ): QiCardPaymentResponse {
        $requestData = $this->buildPaymentRequestData(
            $requestId,
            $amount,
            $currency,
            $customerInfo,
            $browserInfo,
            $additionalInfo,
            $appChannel
        );

        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Terminal-Id' => $this->terminalId,
            ])
            ->post("{$this->baseUrl}/payment", $requestData);

        return new QiCardPaymentResponse($response->json());
    }

    public function getPaymentStatus(string $paymentId): QiCardPaymentResponse
    {
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Terminal-Id' => $this->terminalId,
            ])
            ->get("{$this->baseUrl}/payment/{$paymentId}/status");

        return new QiCardPaymentResponse($response->json());
    }

    public function cancelPayment(string $paymentId, string $requestId, float $amount): QiCardPaymentResponse
    {
        $requestData = [
            'requestId' => $requestId,
            'amount' => $amount,
        ];

        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Terminal-Id' => $this->terminalId,
            ])
            ->post("{$this->baseUrl}/payment/{$paymentId}/cancel", $requestData);

        return new QiCardPaymentResponse($response->json());
    }

    public function refundPayment(string $paymentId, string $requestId, float $amount): QiCardRefundResponse
    {
        $requestData = [
            'requestId' => $requestId,
            'amount' => $amount,
        ];

        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Terminal-Id' => $this->terminalId,
            ])
            ->post("{$this->baseUrl}/payment/{$paymentId}/refund", $requestData);

        return new QiCardRefundResponse($response->json());
    }

    public function verifyWebhookSignature(array $payload, string $signature): bool
    {
        $publicKey = config('qicard.public_key');
        return \TheJano\QiCardPayment\Helpers\WebhookVerifier::verify($payload, $signature, $publicKey);
    }

    protected function buildPaymentRequestData(
        string $requestId,
        float $amount,
        ?string $currency,
        ?CustomerInfo $customerInfo,
        ?BrowserInfo $browserInfo,
        ?array $additionalInfo,
        bool $appChannel
    ): array {
        $data = [
            'requestId' => $requestId,
            'amount' => $amount,
            'currency' => $currency ?? config('qicard.currency'),
            'locale' => config('qicard.locale'),
            'finishPaymentUrl' => config('qicard.finish_url'),
            'notificationUrl' => config('qicard.notification_url'),
            'appChannel' => $appChannel,
        ];

        if ($customerInfo !== null) {
            $data['customerInfo'] = $customerInfo->toApiArray();
        }

        if ($browserInfo !== null) {
            $data['browserInfo'] = $browserInfo->toApiArray();
        }

        if ($additionalInfo !== null) {
            $data['additionalInfo'] = $additionalInfo;
        }

        return $data;
    }
}
