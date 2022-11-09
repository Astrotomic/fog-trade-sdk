<?php

namespace Tests;

use Astrotomic\FogTradeSdk\FogTradeConnector;
use Astrotomic\FogTradeSdk\FogTradeSdkServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Sammyjo20\Saloon\Http\Fixture;
use Sammyjo20\Saloon\Http\MockResponse;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\SaloonLaravel\Facades\Saloon;

abstract class TestCase extends Orchestra
{
    protected $enablesPackageDiscoveries = true;

    protected FogTradeConnector $fog;

    protected function setUp(): void
    {
        parent::setUp();

        Saloon::fake([
            FogTradeConnector::class => function (SaloonRequest $request): Fixture {
                $name = implode('/', array_filter([
                    parse_url($request->getFullRequestUrl(), PHP_URL_HOST),
                    mb_strtoupper($request->getMethod() ?? 'GET'),
                    parse_url($request->getFullRequestUrl(), PHP_URL_PATH),
                    http_build_query($request->getQuery()),
                ]));

                return MockResponse::fixture($name);
            },
        ]);

        $this->fog = new FogTradeConnector();
    }

    protected function getPackageProviders($app): array
    {
        return [
            FogTradeSdkServiceProvider::class,
        ];
    }
}
