<?php

namespace TheJano\QiCardPayment\Facades;

use Illuminate\Support\Facades\Facade;

class QiCardPayment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TheJano\QiCardPayment\Services\QiCardPayment::class;
    }
}
