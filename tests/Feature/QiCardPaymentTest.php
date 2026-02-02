<?php

use Illuminate\Support\Facades\Http;
use TheJano\QiCardPayment\Services\QiCardPayment;
use TheJano\QiCardPayment\Data\CustomerInfo;
use TheJano\QiCardPayment\Data\BrowserInfo;

beforeEach(function () {
    // Reset the singleton for each test
    $reflection = new ReflectionClass(QiCardPayment::class);
    $property = $reflection->getProperty('instance');
    $property->setAccessible(true);
    $property->setValue(null, null);
});

test('it creates payment successfully', function () {
    Http::fake([
        '*/payment' => Http::response([
            'requestId' => '4256ab83-de74-450f-b442-8fb995458243',
            'paymentId' => 'f2bb43a8-488a-4281-977b-5b3418fc3c67',
            'amount' => 256.89,
            'currency' => 'IQD',
            'status' => 'CREATED',
            'creationDate' => '2024-08-04T15:34:33Z',
            'formUrl' => 'https://uat-sandbox-3ds-api.qi.iq/api/v1/payment/f2bb43a8-488a-4281-977b-5b3418fc3c67',
            'canceled' => false,
        ], 200),
    ]);

    $response = QiCardPayment::make()->createPayment(
        'test-request-id',
        256.89,
        'IQD'
    );

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->paymentId)->toBe('f2bb43a8-488a-4281-977b-5b3418fc3c67')
        ->and($response->status)->toBe('CREATED')
        ->and($response->getPaymentUrl())->toContain('f2bb43a8-488a-4281-977b-5b3418fc3c67');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://uat-sandbox-3ds-api.qi.iq/api/v1/payment'
            && $request->method() === 'POST'
            && $request->hasHeader('X-Terminal-Id', '123456')
            && $request['amount'] === 256.89
            && $request['currency'] === 'IQD';
    });
});

test('it creates payment with customer info', function () {
    Http::fake([
        '*/payment' => Http::response([
            'requestId' => 'test-request-id',
            'paymentId' => 'test-payment-id',
            'amount' => 50000,
            'currency' => 'IQD',
            'status' => 'CREATED',
            'formUrl' => 'https://example.com/payment/test-payment-id',
            'canceled' => false,
        ], 200),
    ]);

    $customerInfo = new CustomerInfo([
        'firstName' => 'John',
        'lastName' => 'Doe',
        'phone' => '009647xxxxxxxxx',
        'email' => 'john@example.com',
    ]);

    $response = QiCardPayment::make()->createPayment(
        'test-request-id',
        50000,
        'IQD',
        $customerInfo
    );

    expect($response->isSuccessful())->toBeTrue();

    Http::assertSent(function ($request) {
        return isset($request['customerInfo'])
            && $request['customerInfo']['firstName'] === 'John'
            && $request['customerInfo']['lastName'] === 'Doe';
    });
});

test('it creates payment with browser info', function () {
    Http::fake([
        '*/payment' => Http::response([
            'requestId' => 'test-request-id',
            'paymentId' => 'test-payment-id',
            'status' => 'CREATED',
            'formUrl' => 'https://example.com/payment/test-payment-id',
        ], 200),
    ]);

    $browserInfo = new BrowserInfo([
        'browserIp' => '192.168.1.1',
        'browserUserAgent' => 'Mozilla/5.0',
    ]);

    $response = QiCardPayment::make()->createPayment(
        'test-request-id',
        50000,
        'IQD',
        null,
        $browserInfo
    );

    expect($response->isSuccessful())->toBeTrue();

    Http::assertSent(function ($request) {
        return isset($request['browserInfo'])
            && $request['browserInfo']['browserIp'] === '192.168.1.1';
    });
});

test('it gets payment status successfully', function () {
    Http::fake([
        '*/payment/*/status' => Http::response([
            'requestId' => '618faf78-0ef5-4c19-a06b-86199fb77d02',
            'paymentId' => '56fe7b8d-b5af-4036-8c2d-5d2e7b2faaf7',
            'status' => 'SUCCESS',
            'canceled' => false,
            'amount' => 256.89,
            'confirmedAmount' => 256.89,
            'currency' => 'IQD',
            'paymentType' => 'CARD',
            'details' => [
                'resultCode' => '00',
                'rrn' => '421900005327',
                'authId' => '052045',
                'maskedPan' => '521372******8582',
                'paymentSystem' => 'MASTER_CARD',
            ],
        ], 200),
    ]);

    $response = QiCardPayment::make()->getPaymentStatus('test-payment-id');

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->isPaid())->toBeTrue()
        ->and($response->status)->toBe('SUCCESS')
        ->and($response->paymentType)->toBe('CARD')
        ->and($response->getPaymentSystem())->toBe('MASTER_CARD')
        ->and($response->getMaskedPan())->toBe('521372******8582');

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/payment/test-payment-id/status')
            && $request->method() === 'GET';
    });
});

test('it cancels payment successfully', function () {
    Http::fake([
        '*/payment/*/cancel' => Http::response([
            'requestId' => '142acf33-2146-4f06-b190-b74452b98f82',
            'paymentId' => 'd83c50ba-65c3-41e4-97fc-7209cc29bce4',
            'status' => 'CREATED',
            'canceled' => true,
            'amount' => 256.89,
            'currency' => 'IQD',
            'cancels' => [
                [
                    'requestId' => '5df5c849-9258-4f9b-87d0-f0b467735331',
                    'created' => '2024-08-06T15:26:18Z',
                    'successfully' => true,
                    'amount' => 256.89,
                ],
            ],
        ], 200),
    ]);

    $response = QiCardPayment::make()->cancelPayment(
        'test-payment-id',
        'cancel-request-id',
        256.89
    );

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->canceled)->toBeTrue();

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/payment/test-payment-id/cancel')
            && $request->method() === 'POST'
            && $request['requestId'] === 'cancel-request-id'
            && $request['amount'] === 256.89;
    });
});

test('it processes refund successfully', function () {
    Http::fake([
        '*/payment/*/refund' => Http::response([
            'refundId' => '37b85e60-e7a6-4abb-9466-703472fb83b9',
            'paymentId' => 'test-payment-id',
            'amount' => 100.00,
            'currency' => 'IQD',
            'status' => 'SUCCESS',
            'details' => [
                'resultCode' => '00',
                'resultDescription' => 'Successfully',
            ],
        ], 200),
    ]);

    $response = QiCardPayment::make()->refundPayment(
        'test-payment-id',
        'refund-request-id',
        100.00
    );

    expect($response->isSuccessful())->toBeTrue()
        ->and($response->status)->toBe('SUCCESS');

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/payment/test-payment-id/refund')
            && $request->method() === 'POST'
            && $request['requestId'] === 'refund-request-id'
            && $request['amount'] === 100.00;
    });
});

test('it handles error response from api', function () {
    Http::fake([
        '*/payment' => Http::response([
            'error' => [
                'code' => 18,
                'message' => 'INCORRECT_TRANSFER_STATE',
            ],
        ], 400),
    ]);

    $response = QiCardPayment::make()->createPayment(
        'test-request-id',
        256.89
    );

    expect($response->isSuccessful())->toBeFalse()
        ->and($response->errorCode)->toBe(18)
        ->and($response->errorMessage)->toBe('INCORRECT_TRANSFER_STATE');
});

test('it sends correct authentication headers', function () {
    Http::fake([
        '*/payment' => Http::response([
            'paymentId' => 'test-id',
            'status' => 'CREATED',
        ], 200),
    ]);

    QiCardPayment::make()->createPayment('test-request-id', 100);

    Http::assertSent(function ($request) {
        return $request->hasHeader('Authorization')
            && $request->hasHeader('X-Terminal-Id')
            && $request->hasHeader('Content-Type', 'application/json')
            && $request->hasHeader('Accept', 'application/json');
    });
});
