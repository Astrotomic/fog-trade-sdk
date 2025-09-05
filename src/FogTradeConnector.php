<?php

namespace Astrotomic\FogTradeSdk;

use Astrotomic\FogTradeSdk\Data\Appeal;
use Astrotomic\FogTradeSdk\Data\Report;
use Astrotomic\FogTradeSdk\Requests\GetAppealsRequest;
use Astrotomic\FogTradeSdk\Requests\GetReportsRequest;
use Illuminate\Support\LazyCollection;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\OffsetPaginator;
use Saloon\PaginationPlugin\Paginator;
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
            ->map(fn (array $item) => Appeal::from($item));
    }

    public function reports(
        array $selectedStates,
        bool $archived = true,
    ): LazyCollection {
        return $this->paginate(new GetReportsRequest($archived, $selectedStates))
            ->collect()
            ->map(fn (array $item) => Report::from($item));
    }

    public function paginate(Request $request): Paginator
    {
        return new class(connector: $this, request: $request) extends OffsetPaginator
        {
            protected ?int $perPageLimit = 100;

            protected function isLastPage(Response $response): bool
            {
                return $this->getOffset() >= (int) $response->json('recordsTotal');
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->json('data');
            }

            protected function applyPagination(Request $request): Request
            {
                $request->query()->merge([
                    'length' => $this->perPageLimit,
                    'start' => $this->getOffset(),
                ]);

                return $request;
            }
        };
    }
}
