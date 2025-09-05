<?php

namespace Tests;

use Astrotomic\FogTradeSdk\FogTradeConnector;
use Astrotomic\FogTradeSdk\FogTradeSdkServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Http\Faking\Fixture;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Laravel\Facades\Saloon;

abstract class TestCase extends Orchestra
{
    protected $enablesPackageDiscoveries = true;

    protected FogTradeConnector $fog;

    protected function setUp(): void
    {
        parent::setUp();

        Saloon::fake([
            FogTradeConnector::class => function (PendingRequest $request): Fixture {
                $name = implode('/', array_filter([
                    parse_url($request->getUrl(), PHP_URL_HOST),
                    $request->getMethod()->value,
                    parse_url($request->getUrl(), PHP_URL_PATH),
                    http_build_query($request->query()->all()),
                ]));

                return new Fixture($name);
            },
        ]);

        $this->fog = new FogTradeConnector;
    }

    protected function getPackageProviders($app): array
    {
        return [
            FogTradeSdkServiceProvider::class,
        ];
    }
}
