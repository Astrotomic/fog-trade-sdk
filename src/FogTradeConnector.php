<?php

namespace Astrotomic\FogTradeSdk;

use Astrotomic\FogTradeSdk\Requests\GetAppealsRequest;
use Astrotomic\FogTradeSdk\Requests\GetReportsRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Saloon\Contracts\HasPagination;
use Saloon\Contracts\Paginator;
use Saloon\Contracts\Request;
use Saloon\Http\Connector;
use Saloon\Http\Paginators\OffsetPaginator;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class FogTradeConnector extends Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public function resolveBaseUrl(): string
    {
        return 'https://f-o-g.trade';
    }

    public function appeals(
        array $selectedStates,
        bool $archived = true,
    ): LazyCollection {
        return $this->paginate(new GetAppealsRequest($archived, $selectedStates))
            ->collect()
            ->map(fn (Response $response): Collection => $response->dto()->toCollection())
            ->collapse();
    }

    public function reports(
        array $selectedStates,
        bool $archived = true,
    ): LazyCollection {
        return $this->paginate(new GetReportsRequest($archived, $selectedStates))
            ->collect()
            ->map(fn (Response $response): Collection => $response->dto()->toCollection())
            ->collapse();
    }

    public function paginate(Request $request, mixed ...$additionalArguments): Paginator
    {
        $paginator = new OffsetPaginator(
            connector: $this,
            originalRequest: $request,
            limit: 100,
        );

        $paginator->setLimitKeyName('length');
        $paginator->setOffsetKeyName('start');
        $paginator->setTotalKeyName('recordsTotal');

        return $paginator;
    }
}
