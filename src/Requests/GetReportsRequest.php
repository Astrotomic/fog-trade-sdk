<?php

namespace Astrotomic\FogTradeSdk\Requests;

use Astrotomic\FogTradeSdk\Data\Report;
use Astrotomic\FogTradeSdk\Enums\ReportState;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Request\CreatesDtoFromResponse;
use Spatie\LaravelData\DataCollection;

class GetReportsRequest extends Request implements Paginatable
{
    use CreatesDtoFromResponse;

    protected Method $method = Method::GET;

    public function __construct(
        public readonly bool $archived,
        public readonly array $selectedStates,
        public readonly int $start = 0,
        public readonly int $length = 20,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/reports/processData';
    }

    public function defaultQuery(): array
    {
        return [
            'start' => $this->start,
            'length' => $this->length,
            'archived' => $this->archived,
            'selectedStates' => array_map(
                fn (ReportState $state): int => $state->value,
                $this->selectedStates
            ),
        ];
    }

    /**
     * @return Collection<array-key, Report>
     */
    public function createDtoFromResponse(Response $response): DataCollection
    {
        return collect(Report::collect($response->json('data')));
    }
}
