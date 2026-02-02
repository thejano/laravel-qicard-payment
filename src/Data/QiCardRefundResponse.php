<?php

namespace TheJano\QiCardPayment\Data;

use TheJano\QiCardPayment\Traits\CastableToArrayTrait;

class QiCardRefundResponse
{
    use CastableToArrayTrait;

    public bool $success;
    public ?string $requestId;
    public ?string $refundId;
    public ?string $paymentId;
    public ?float $amount;
    public ?string $currency;
    public ?string $status;
    public ?string $message;
    public ?string $creationDate;
    public ?array $details;
    public ?string $errorMessage;
    public ?int $errorCode;

    public function __construct(?array $response)
    {
        if ($response === null) {
            $this->initializeEmpty();
            return;
        }

        $this->success = !isset($response['error']);
        $this->requestId = $response['requestId'] ?? null;
        $this->refundId = $response['refundId'] ?? null;
        $this->paymentId = $response['paymentId'] ?? null;
        $this->amount = $response['amount'] ?? null;
        $this->currency = $response['currency'] ?? null;
        $this->status = $response['status'] ?? null;
        $this->message = $response['message'] ?? null;
        $this->creationDate = $response['creationDate'] ?? null;
        $this->details = $response['details'] ?? null;
        $this->errorMessage = $response['error']['message'] ?? null;
        $this->errorCode = $response['error']['code'] ?? null;
    }

    private function initializeEmpty(): void
    {
        $this->success = false;
        $this->requestId = null;
        $this->refundId = null;
        $this->paymentId = null;
        $this->amount = null;
        $this->currency = null;
        $this->status = null;
        $this->message = null;
        $this->creationDate = null;
        $this->details = null;
        $this->errorMessage = null;
        $this->errorCode = null;
    }

    public function isSuccessful(): bool
    {
        return $this->success && $this->status === 'SUCCESS';
    }

    /**
     * Get masked card number from refund details
     */
    public function getMaskedPan(): ?string
    {
        return $this->details['maskedPan'] ?? null;
    }

    /**
     * Get payment system (VISA, MASTER_CARD, etc.)
     */
    public function getPaymentSystem(): ?string
    {
        return $this->details['paymentSystem'] ?? null;
    }

    /**
     * Get RRN (Retrieval Reference Number)
     */
    public function getRrn(): ?string
    {
        return $this->details['rrn'] ?? null;
    }
}
