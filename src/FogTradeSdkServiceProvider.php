<?php

namespace Astrotomic\FogTradeSdk;

use Illuminate\Support\ServiceProvider;

class FogTradeSdkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FogTradeConnector::class, function (): FogTradeConnector {
            return new FogTradeConnector;
        });
    }
}
