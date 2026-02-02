<?php

use TheJano\QiCardPayment\Helpers\TransactionHelper;

test('it generates unique request id', function () {
    $requestId1 = TransactionHelper::generateRequestId();
    $requestId2 = TransactionHelper::generateRequestId();

    expect($requestId1)->toBeString()
        ->and($requestId2)->toBeString()
        ->and($requestId1)->not->toBe($requestId2);
});

test('it generates uuid format request id', function () {
    $requestId = TransactionHelper::generateRequestId();

    // UUID format: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
    expect($requestId)->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i');
});

test('it formats amount correctly', function () {
    expect(TransactionHelper::formatAmount(100))->toBe(100.00)
        ->and(TransactionHelper::formatAmount(100.5))->toBe(100.50)
        ->and(TransactionHelper::formatAmount(100.999))->toBe(101.00)
        ->and(TransactionHelper::formatAmount(0))->toBe(0.00);
});
