<?php

use TheJano\QiCardPayment\Data\QiCardRefundResponse;

test('it parses successful refund response', function () {
    $responseData = [
        'refundId' => '71dbae1e-635a-4025-b3cb-0721b5974eea',
        'requestId' => '170685f5-572c-47a4-a6af-b742f39a9b2c',
        'status' => 'SUCCESS',
        'paymentId' => '7314a5ea-20fb-455d-990f-dc02cb97219d',
        'creationDate' => '2026-02-02T14:59:57',
        'amount' => 50000,
        'currency' => 'IQD',
        'message' => 'Marriage',
        'details' => [
            'resultCode' => '00',
            'rrn' => '603300031868',
            'authId' => '123456',
            'authDate' => '2026-02-02T14:59:57',
            'maskedPan' => '521372******8582',
            'paymentSystem' => 'MASTER_CARD',
        ],
    ];

    $response = new QiCardRefundResponse($responseData);

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->refundId)->toBe('71dbae1e-635a-4025-b3cb-0721b5974eea')
        ->and($response->paymentId)->toBe('7314a5ea-20fb-455d-990f-dc02cb97219d')
        ->and($response->amount)->toBe(50000.0)
        ->and($response->currency)->toBe('IQD')
        ->and($response->status)->toBe('SUCCESS')
        ->and($response->message)->toBe('Marriage')
        ->and($response->getMaskedPan())->toBe('521372******8582')
        ->and($response->getPaymentSystem())->toBe('MASTER_CARD')
        ->and($response->getRrn())->toBe('603300031868');
});

test('it parses error refund response with INCORRECT_TRANSFER_STATE', function () {
    $responseData = [
        'error' => [
            'code' => 18,
            'message' => 'INCORRECT_TRANSFER_STATE',
        ],
    ];

    $response = new QiCardRefundResponse($responseData);

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->errorCode)->toBe(18)
        ->and($response->errorMessage)->toBe('INCORRECT_TRANSFER_STATE');
});

test('it handles null refund response gracefully', function () {
    $response = new QiCardRefundResponse(null);

    expect($response->isSuccessful())->toBeFalse();
});

test('it converts refund response to array', function () {
    $responseData = [
        'refundId' => 'test-refund-id',
        'paymentId' => 'test-payment-id',
        'amount' => 100.00,
        'status' => 'SUCCESS',
    ];

    $response = new QiCardRefundResponse($responseData);
    $array = $response->toArray();

    expect($array)->toBeArray()
        ->and($array['refundId'])->toBe('test-refund-id')
        ->and($array['paymentId'])->toBe('test-payment-id');
});
