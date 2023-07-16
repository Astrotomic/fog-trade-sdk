<?php

namespace Astrotomic\FogTradeSdk;

use Astrotomic\FogTradeSdk\Requests\GetAppealsRequest;
use Astrotomic\FogTradeSdk\Requests\GetReportsRequest;
use Astrotomic\FogTradeSdk\Responses\FogTradeResponse;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Spatie\LaravelData\DataCollection;

class FogTradeConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    protected ?string $response = FogTradeResponse::class;

    public function resolveBaseUrl(): string
    {
        return 'https://f-o-g.trade';
    }

    public function getReports(
        bool $archived,
        array $selectedStates,
        int $start = 0,
        int $length = 20,
    ): DataCollection {
        return $this->send(
            new GetReportsRequest($archived, $selectedStates, $start, $length)
        )->dto();
    }

    public function getAppeals(
        bool $archived,
        array $selectedStates,
        int $start = 0,
        int $length = 20,
    ): DataCollection {
        return $this->send(
            new GetAppealsRequest($archived, $selectedStates, $start, $length)
        )->dto();
    }
}
