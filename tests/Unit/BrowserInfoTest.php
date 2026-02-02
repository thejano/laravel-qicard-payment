<?php

use TheJano\QiCardPayment\Data\BrowserInfo;

test('it creates browser info with all fields', function () {
    $data = [
        'browserAcceptHeader' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'browserIp' => '140.82.118.3',
        'browserJavaEnabled' => false,
        'browserLanguage' => 'en-US',
        'browserColorDepth' => '24',
        'browserScreenWidth' => '1024',
        'browserScreenHeight' => '768',
        'browserTZ' => '-180',
        'browserUserAgent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:77.0) Gecko/20100101 Firefox/77.0',
    ];

    $browserInfo = new BrowserInfo($data);
    $array = $browserInfo->toApiArray();

    expect($array['browserIp'])->toBe('140.82.118.3')
        ->and($array['browserLanguage'])->toBe('en-US')
        ->and($array['browserScreenWidth'])->toBe('1024')
        ->and($array['browserScreenHeight'])->toBe('768')
        ->and($array['browserJavaEnabled'])->toBeFalse();
});

test('it creates browser info with minimal fields', function () {
    $data = [
        'browserIp' => '192.168.1.1',
        'browserUserAgent' => 'Mozilla/5.0',
    ];

    $browserInfo = new BrowserInfo($data);
    $array = $browserInfo->toApiArray();

    expect($array['browserIp'])->toBe('192.168.1.1')
        ->and($array['browserUserAgent'])->toBe('Mozilla/5.0');
});

test('it excludes null values from api array', function () {
    $data = [
        'browserIp' => '192.168.1.1',
    ];

    $browserInfo = new BrowserInfo($data);
    $array = $browserInfo->toApiArray();

    expect($array)->toHaveKey('browserIp')
        ->and($array)->not->toHaveKey('browserLanguage')
        ->and($array)->not->toHaveKey('browserUserAgent');
});

test('it converts browser info to array', function () {
    $data = [
        'browserIp' => '192.168.1.1',
    ];

    $browserInfo = new BrowserInfo($data);
    $array = $browserInfo->toArray();

    expect($array)->toBeArray();
});
