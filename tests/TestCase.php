<?php

namespace Tests;

use Astrotomic\FogTradeSdk\FogTradeConnector;
use Astrotomic\FogTradeSdk\FogTradeSdkServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Http\Faking\Fixture;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\PendingRequest;

abstract class TestCase extends Orchestra
{
    protected $enablesPackageDiscoveries = true;

    protected FogTradeConnector $fog;

    protected function setUp(): void
    {
        parent::setUp();

        MockClient::global([
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

    protected function tearDown(): void
    {
        MockClient::destroyGlobal();

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            FogTradeSdkServiceProvider::class,
        ];
    }
}
