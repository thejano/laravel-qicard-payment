<?php

namespace TheJano\QiCardPayment\Data;

use TheJano\QiCardPayment\Traits\CastableToArrayTrait;

class BrowserInfo
{
    use CastableToArrayTrait;

    public ?string $browserAcceptHeader;
    public ?string $browserIp;
    public ?bool $browserJavaEnabled;
    public ?string $browserLanguage;
    public ?string $browserColorDepth;
    public ?string $browserScreenWidth;
    public ?string $browserScreenHeight;
    public ?string $browserTZ;
    public ?string $browserUserAgent;

    public function __construct(array $data = [])
    {
        $this->browserAcceptHeader = $data['browserAcceptHeader'] ?? null;
        $this->browserIp = $data['browserIp'] ?? null;
        $this->browserJavaEnabled = $data['browserJavaEnabled'] ?? null;
        $this->browserLanguage = $data['browserLanguage'] ?? null;
        $this->browserColorDepth = $data['browserColorDepth'] ?? null;
        $this->browserScreenWidth = $data['browserScreenWidth'] ?? null;
        $this->browserScreenHeight = $data['browserScreenHeight'] ?? null;
        $this->browserTZ = $data['browserTZ'] ?? null;
        $this->browserUserAgent = $data['browserUserAgent'] ?? null;
    }

    public function toApiArray(): array
    {
        return array_filter($this->toArray(), fn($value) => $value !== null);
    }
}
