<?php

namespace TheJano\QiCardPayment\Data;

use InvalidArgumentException;

class CreatePaymentParams
{
    public string $requestId;
    public float $amount;
    public ?string $currency = null;
    public ?CustomerInfo $customerInfo = null;
    public ?BrowserInfo $browserInfo = null;
    public ?array $additionalInfo = null;


    public function __construct() {}

    public function setRequestId(string $requestId): self
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function setCustomerInfo(CustomerInfo $customerInfo): self
    {
        $this->customerInfo = $customerInfo;
        return $this;
    }

    public function setBrowserInfo(BrowserInfo $browserInfo): self
    {
        $this->browserInfo = $browserInfo;
        return $this;
    }

    public function setAdditionalInfo(array $additionalInfo): self
    {
        $this->additionalInfo = $additionalInfo;
        return $this;
    }

    public function toArray(): array
    {
        if ($this->requestId || $this->amount) {
            throw new InvalidArgumentException('Request ID, amount are required');
        }

        $data = [
            'requestId' => $this->requestId,
            'amount' => $this->amount,
            'currency' => $this->currency ?? config('qicard.currency'),
            'locale' => config('qicard.locale'),
            'finishPaymentUrl' => config('qicard.finish_url'),
            'notificationUrl' => config('qicard.notification_url'),
        ];

        if ($this->customerInfo !== null) {
            $data['customerInfo'] = $this->customerInfo->toApiArray();
        }

        if ($this->browserInfo !== null) {
            $data['browserInfo'] = $this->browserInfo->toApiArray();
        }

        if ($this->additionalInfo !== null) {
            $data['additionalInfo'] = $this->additionalInfo;
        }

        return $data;
    }
}
