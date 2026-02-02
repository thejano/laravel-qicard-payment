<?php

use TheJano\QiCardPayment\Data\QiCardPaymentResponse;
use TheJano\QiCardPayment\Enums\PaymentStatus;

// Create Payment Response Tests
test('it parses successful create payment response', function () {
    $responseData = [
        'requestId' => '4ec3a4cc-6c07-4d08-ace0-770fbaae519a',
        'paymentId' => '7314a5ea-20fb-455d-990f-dc02cb97219d',
        'status' => 'CREATED',
        'canceled' => false,
        'amount' => 50000,
        'currency' => 'IQD',
        'creationDate' => '2026-02-02T14:54:37',
        'formUrl' => 'https://uat-sandbox-3ds-api.qi.iq/api/v1/payment/7314a5ea-20fb-455d-990f-dc02cb97219d',
        'withoutAuthenticate' => false,
    ];

    $response = new QiCardPaymentResponse($responseData);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->isCreated())->toBeTrue()
        ->and($response->requestId)->toBe('4ec3a4cc-6c07-4d08-ace0-770fbaae519a')
        ->and($response->paymentId)->toBe('7314a5ea-20fb-455d-990f-dc02cb97219d')
        ->and($response->amount)->toBe(50000.0)
        ->and($response->currency)->toBe('IQD')
        ->and($response->status)->toBe('CREATED')
        ->and($response->canceled)->toBeFalse()
        ->and($response->withoutAuthenticate)->toBeFalse()
        ->and($response->getPaymentUrl())->toBe('https://uat-sandbox-3ds-api.qi.iq/api/v1/payment/7314a5ea-20fb-455d-990f-dc02cb97219d');
});

test('it parses FORM_SHOWED status response', function () {
    $responseData = [
        'requestId' => '4ec3a4cc-6c07-4d08-ace0-770fbaae519a',
        'paymentId' => '7314a5ea-20fb-455d-990f-dc02cb97219d',
        'status' => 'FORM_SHOWED',
        'canceled' => false,
        'amount' => 50000.000,
        'currency' => 'IQD',
        'creationDate' => '2026-02-02T12:01:19',
        'withoutAuthenticate' => false,
        'additionalInfo' => [],
    ];

    $response = new QiCardPaymentResponse($responseData);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->isFormShowed())->toBeTrue()
        ->and($response->isPaid())->toBeFalse()
        ->and($response->status)->toBe('FORM_SHOWED');
});

test('it parses successful paid payment status response', function () {
    $responseData = [
        'requestId' => '4ec3a4cc-6c07-4d08-ace0-770fbaae519a',
        'paymentId' => '7314a5ea-20fb-455d-990f-dc02cb97219d',
        'status' => 'SUCCESS',
        'canceled' => false,
        'amount' => 50000.000,
        'confirmedAmount' => 50000.000,
        'currency' => 'IQD',
        'paymentType' => 'CARD',
        'creationDate' => '2026-02-02T11:54:37',
        'details' => [
            'resultCode' => '00',
            'rrn' => '603300031868',
            'authId' => '123456',
            'authDate' => '2026-02-02T11:57:20',
            'maskedPan' => '521372******8582',
            'paymentSystem' => 'MASTER_CARD',
            'customDetails' => [],
        ],
        'withoutAuthenticate' => false,
        'additionalInfo' => [],
    ];

    $response = new QiCardPaymentResponse($responseData);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->isPaid())->toBeTrue()
        ->and($response->status)->toBe('SUCCESS')
        ->and($response->confirmedAmount)->toBe(50000.000)
        ->and($response->paymentType)->toBe('CARD')
        ->and($response->getMaskedPan())->toBe('521372******8582')
        ->and($response->getPaymentSystem())->toBe('MASTER_CARD')
        ->and($response->getAuthId())->toBe('123456')
        ->and($response->getRrn())->toBe('603300031868');
});

test('it parses AUTHENTICATION_FAILED status response', function () {
    $responseData = [
        'requestId' => 'MAHALsdd4cc-saae519a',
        'paymentId' => '9a914508-b03d-45d2-9e0e-3f918801bef1',
        'status' => 'AUTHENTICATION_FAILED',
        'canceled' => false,
        'amount' => 50000.000,
        'currency' => 'IQD',
        'paymentType' => 'CARD',
        'creationDate' => '2026-02-02T12:07:06',
        'withoutAuthenticate' => false,
        'additionalInfo' => [],
    ];

    $response = new QiCardPaymentResponse($responseData);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->isAuthenticationFailed())->toBeTrue()
        ->and($response->isPaid())->toBeFalse()
        ->and($response->status)->toBe('AUTHENTICATION_FAILED');
});

test('it parses canceled payment response when form showed', function () {
    $responseData = [
        'requestId' => 'MAHALA-4ec3a4cc-770fbaae519a',
        'paymentId' => '24c81162-b4c5-45d6-a049-b318781a2803',
        'status' => 'FORM_SHOWED',
        'canceled' => true,
        'amount' => 50000.000,
        'currency' => 'IQD',
        'creationDate' => '2026-02-02T12:01:19',
        'cancels' => [
            [
                'requestId' => 'MAHALA-bsdd4b81e54d',
                'created' => '2026-02-02T15:03:04',
                'successfully' => true,
                'amount' => 50000.000,
            ],
        ],
        'withoutAuthenticate' => false,
        'additionalInfo' => [],
    ];

    $response = new QiCardPaymentResponse($responseData);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->isCanceled())->toBeTrue()
        ->and($response->canceled)->toBeTrue()
        ->and($response->cancels)->toHaveCount(1)
        ->and($response->cancels[0]['successfully'])->toBeTrue();
});

test('it parses canceled payment response when only created', function () {
    $responseData = [
        'requestId' => 'MAHALA-4ec3a4cc-saae519a',
        'paymentId' => '52294565-02b5-46e9-9665-f2152ee3a5d6',
        'status' => 'CREATED',
        'canceled' => true,
        'amount' => 50000.000,
        'currency' => 'IQD',
        'creationDate' => '2026-02-02T12:04:01',
        'cancels' => [
            [
                'requestId' => 'MAHALA-bsdd4bddsdsd81e54d',
                'created' => '2026-02-02T15:04:20',
                'successfully' => true,
                'amount' => 50000.000,
            ],
        ],
        'withoutAuthenticate' => false,
        'additionalInfo' => [],
    ];

    $response = new QiCardPaymentResponse($responseData);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->isCreated())->toBeTrue()
        ->and($response->isCanceled())->toBeTrue();
});

test('it parses error response with INCORRECT_TRANSFER_STATE', function () {
    $responseData = [
        'error' => [
            'code' => 18,
            'message' => 'INCORRECT_TRANSFER_STATE',
        ],
    ];

    $response = new QiCardPaymentResponse($responseData);

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->errorCode)->toBe(18)
        ->and($response->errorMessage)->toBe('INCORRECT_TRANSFER_STATE');
});

test('it handles null response gracefully', function () {
    $response = new QiCardPaymentResponse(null);

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->isPaid())->toBeFalse()
        ->and($response->isCanceled())->toBeFalse();
});

test('it converts payment response to array', function () {
    $responseData = [
        'requestId' => 'test-request-id',
        'paymentId' => 'test-payment-id',
        'amount' => 100.00,
        'currency' => 'IQD',
        'status' => 'CREATED',
    ];

    $response = new QiCardPaymentResponse($responseData);
    $array = $response->toArray();

    expect($array)->toBeArray()
        ->and($array['requestId'])->toBe('test-request-id')
        ->and($array['paymentId'])->toBe('test-payment-id');
});

test('it uses PaymentStatus enum correctly', function () {
    expect(PaymentStatus::CREATED->value)->toBe('CREATED')
        ->and(PaymentStatus::FORM_SHOWED->value)->toBe('FORM_SHOWED')
        ->and(PaymentStatus::SUCCESS->value)->toBe('SUCCESS')
        ->and(PaymentStatus::FAILED->value)->toBe('FAILED')
        ->and(PaymentStatus::AUTHENTICATION_FAILED->value)->toBe('AUTHENTICATION_FAILED');
});
