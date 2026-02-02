<?php

namespace TheJano\QiCardPayment\Data;

use TheJano\QiCardPayment\Traits\CastableToArrayTrait;

class CustomerInfo
{
    use CastableToArrayTrait;

    public ?string $firstName;
    public ?string $middleName;
    public ?string $lastName;
    public ?string $phone;
    public ?string $email;
    public ?string $accountId;
    public ?string $accountNumber;
    public ?string $address;
    public ?string $city;
    public ?string $provinceCode;
    public ?string $countryCode;
    public ?string $postalCode;
    public ?string $birthDate;
    public ?string $identificationType;
    public ?string $identificationNumber;
    public ?string $identificationCountryCode;
    public ?string $identificationExpirationDate;
    public ?string $nationality;
    public ?string $countryOfBirth;
    public ?string $fundSource;
    public ?string $participantId;
    public ?string $additionalMessage;
    public ?string $transactionReason;
    public ?string $claimCode;

    public function __construct(array $data = [])
    {
        $this->firstName = $data['firstName'] ?? null;
        $this->middleName = $data['middleName'] ?? null;
        $this->lastName = $data['lastName'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->accountId = $data['accountId'] ?? null;
        $this->accountNumber = $data['accountNumber'] ?? null;
        $this->address = $data['address'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->provinceCode = $data['provinceCode'] ?? null;
        $this->countryCode = $data['countryCode'] ?? null;
        $this->postalCode = $data['postalCode'] ?? null;
        $this->birthDate = $data['birthDate'] ?? null;
        $this->identificationType = $data['identificationType'] ?? null;
        $this->identificationNumber = $data['identificationNumber'] ?? null;
        $this->identificationCountryCode = $data['identificationCountryCode'] ?? null;
        $this->identificationExpirationDate = $data['identificationExpirationDate'] ?? null;
        $this->nationality = $data['nationality'] ?? null;
        $this->countryOfBirth = $data['countryOfBirth'] ?? null;
        $this->fundSource = $data['fundSource'] ?? null;
        $this->participantId = $data['participantId'] ?? null;
        $this->additionalMessage = $data['additionalMessage'] ?? null;
        $this->transactionReason = $data['transactionReason'] ?? null;
        $this->claimCode = $data['claimCode'] ?? null;
    }

    public function toApiArray(): array
    {
        return array_filter($this->toArray(), fn($value) => $value !== null);
    }
}
