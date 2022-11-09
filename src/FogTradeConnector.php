<?php

namespace Astrotomic\FogTradeSdk;

use Astrotomic\FogTradeSdk\Collections\AppealCollection;
use Astrotomic\FogTradeSdk\Collections\ReportCollection;
use Astrotomic\FogTradeSdk\Requests\GetAppealsRequest;
use Astrotomic\FogTradeSdk\Requests\GetReportsRequest;
use Astrotomic\FogTradeSdk\Responses\FogTradeResponse;
use Sammyjo20\Saloon\Http\SaloonConnector;
use Sammyjo20\Saloon\Traits\Plugins\AcceptsJson;
use Sammyjo20\Saloon\Traits\Plugins\AlwaysThrowsOnErrors;

class FogTradeConnector extends SaloonConnector
{
    use AcceptsJson;
    use AlwaysThrowsOnErrors;

    protected ?string $response = FogTradeResponse::class;

    public function defineBaseUrl(): string
    {
        return 'https://f-o-g.trade';
    }

    public function getReports(
        bool $archived,
        array $selectedStates,
        int $start = 0,
        int $length = 20,
    ): ReportCollection {
        return $this->send(
            new GetReportsRequest($archived, $selectedStates, $start, $length)
        )->dto();
    }

    public function getAppeals(
        bool $archived,
        array $selectedStates,
        int $start = 0,
        int $length = 20,
    ): AppealCollection {
        return $this->send(
            new GetAppealsRequest($archived, $selectedStates, $start, $length)
        )->dto();
    }
}
