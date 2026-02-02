<?php

namespace TheJano\QiCardPayment\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TheJano\QiCardPayment\Providers\QiCardPaymentServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            QiCardPaymentServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('qicard', [
            'username' => 'test_username',
            'password' => 'test_password',
            'terminal_id' => '123456',
            'base_url' => 'https://uat-sandbox-3ds-api.qi.iq/api/v1',
            'currency' => 'IQD',
            'locale' => 'en_US',
            'finish_url' => 'https://example.com/payment/finish',
            'notification_url' => 'https://example.com/payment/notification',
        ]);
    }
}
