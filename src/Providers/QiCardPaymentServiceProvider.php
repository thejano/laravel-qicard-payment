<?php

namespace TheJano\QiCardPayment\Providers;

use Illuminate\Support\ServiceProvider;
use TheJano\QiCardPayment\Services\QiCardPayment;

class QiCardPaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/qicard.php' => config_path('qicard.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/qicard.php', 'qicard');

        $this->app->singleton(QiCardPayment::class, function ($app) {
            return QiCardPayment::make();
        });
    }
}
