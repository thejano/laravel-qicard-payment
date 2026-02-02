<?php

use TheJano\QiCardPayment\Data\CustomerInfo;

test('it creates customer info with all fields', function () {
    $data = [
        'firstName' => 'John',
        'middleName' => 'Mark',
        'lastName' => 'Doe',
        'phone' => '009647xxxxxxxxx',
        'email' => 'j.doe@gmail.com',
        'accountId' => 'j.doe',
        'accountNumber' => '40817123456787852369',
        'address' => '57th street 26',
        'city' => 'Baghdad',
        'provinceCode' => 'BGD',
        'countryCode' => 'IQ',
        'postalCode' => '10069',
        'birthDate' => '07201985',
        'identificationType' => '00',
        'identificationNumber' => '4605 555555',
        'identificationCountryCode' => 'IQ',
        'identificationExpirationDate' => '03122024',
        'nationality' => 'IQ',
        'countryOfBirth' => 'IQ',
        'fundSource' => '01',
        'participantId' => 'someParticipantId',
        'additionalMessage' => 'Transfer of funds, excluding VAT',
        'transactionReason' => '00',
        'claimCode' => '123456789',
    ];

    $customerInfo = new CustomerInfo($data);
    $array = $customerInfo->toApiArray();

    expect($array['firstName'])->toBe('John')
        ->and($array['lastName'])->toBe('Doe')
        ->and($array['email'])->toBe('j.doe@gmail.com')
        ->and($array['city'])->toBe('Baghdad')
        ->and($array['countryCode'])->toBe('IQ');
});

test('it creates customer info with minimal fields', function () {
    $data = [
        'firstName' => 'John',
        'lastName' => 'Doe',
        'phone' => '009647xxxxxxxxx',
    ];

    $customerInfo = new CustomerInfo($data);
    $array = $customerInfo->toApiArray();

    expect($array['firstName'])->toBe('John')
        ->and($array['lastName'])->toBe('Doe')
        ->and($array['phone'])->toBe('009647xxxxxxxxx');
});

test('it excludes null values from api array', function () {
    $data = [
        'firstName' => 'John',
        'lastName' => 'Doe',
    ];

    $customerInfo = new CustomerInfo($data);
    $array = $customerInfo->toApiArray();

    expect($array)->toHaveKey('firstName')
        ->and($array)->toHaveKey('lastName')
        ->and($array)->not->toHaveKey('middleName')
        ->and($array)->not->toHaveKey('email');
});

test('it converts customer info to array', function () {
    $data = [
        'firstName' => 'John',
        'lastName' => 'Doe',
    ];

    $customerInfo = new CustomerInfo($data);
    $array = $customerInfo->toArray();

    expect($array)->toBeArray();
});
