<?php

namespace TheJano\QiCardPayment\Data;

use TheJano\QiCardPayment\Enums\PaymentStatus;
use TheJano\QiCardPayment\Traits\CastableToArrayTrait;

class QiCardPaymentResponse
{
    use CastableToArrayTrait;

    public bool $success;
    public ?string $requestId;
    public ?string $paymentId;
    public ?float $amount;
    public ?float $confirmedAmount;
    public ?string $currency;
    public ?string $status;
    public ?string $paymentType;
    public ?string $creationDate;
    public ?string $formUrl;
    public bool $canceled;
    public bool $withoutAuthenticate;
    public ?array $details;
    public ?array $cancels;
    public ?array $additionalInfo;
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
        $this->paymentId = $response['paymentId'] ?? null;
        $this->amount = $response['amount'] ?? null;
        $this->confirmedAmount = $response['confirmedAmount'] ?? null;
        $this->currency = $response['currency'] ?? null;
        $this->status = $response['status'] ?? null;
        $this->paymentType = $response['paymentType'] ?? null;
        $this->creationDate = $response['creationDate'] ?? null;
        $this->formUrl = $response['formUrl'] ?? null;
        $this->canceled = $response['canceled'] ?? false;
        $this->withoutAuthenticate = $response['withoutAuthenticate'] ?? false;
        $this->details = $response['details'] ?? null;
        $this->cancels = $response['cancels'] ?? null;
        $this->additionalInfo = $response['additionalInfo'] ?? null;
        $this->errorMessage = $response['error']['message'] ?? null;
        $this->errorCode = $response['error']['code'] ?? null;
    }

    private function initializeEmpty(): void
    {
        $this->success = false;
        $this->requestId = null;
        $this->paymentId = null;
        $this->amount = null;
        $this->confirmedAmount = null;
        $this->currency = null;
        $this->status = null;
        $this->paymentType = null;
        $this->creationDate = null;
        $this->formUrl = null;
        $this->canceled = false;
        $this->withoutAuthenticate = false;
        $this->details = null;
        $this->cancels = null;
        $this->additionalInfo = null;
        $this->errorMessage = null;
        $this->errorCode = null;
    }

    public function getPaymentUrl(): ?string
    {
        return $this->formUrl;
    }

    public function isSuccessful(): bool
    {
        return $this->success;
    }

    public function isPaid(): bool
    {
        return $this->status === PaymentStatus::SUCCESS->value;
    }

    public function isCreated(): bool
    {
        return $this->status === PaymentStatus::CREATED->value;
    }

    public function isFormShowed(): bool
    {
        return $this->status === PaymentStatus::FORM_SHOWED->value;
    }

    public function isFailed(): bool
    {
        return $this->status === PaymentStatus::FAILED->value;
    }

    public function isAuthenticationFailed(): bool
    {
        return $this->status === PaymentStatus::AUTHENTICATION_FAILED->value;
    }

    public function isCanceled(): bool
    {
        return $this->canceled;
    }

    /**
     * Get masked card number from payment details
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
     * Get authorization ID
     */
    public function getAuthId(): ?string
    {
        return $this->details['authId'] ?? null;
    }

    /**
     * Get RRN (Retrieval Reference Number)
     */
    public function getRrn(): ?string
    {
        return $this->details['rrn'] ?? null;
    }
}
