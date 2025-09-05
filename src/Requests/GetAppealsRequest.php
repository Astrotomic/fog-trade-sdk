<?php

namespace Astrotomic\FogTradeSdk\Requests;

use Astrotomic\FogTradeSdk\Data\Appeal;
use Astrotomic\FogTradeSdk\Enums\AppealState;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Request\CreatesDtoFromResponse;

class GetAppealsRequest extends Request implements Paginatable
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
        return '/appeals/processData';
    }

    public function defaultQuery(): array
    {
        return [
            'start' => $this->start,
            'length' => $this->length,
            'archived' => $this->archived,
            'selectedStates' => array_map(
                fn (AppealState $state): int => $state->value,
                $this->selectedStates
            ),
        ];
    }

    /**
     * @return Collection<array-key, Appeal>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return collect(Appeal::collect($response->json('data')));
    }
}
