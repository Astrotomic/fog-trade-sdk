<?php

namespace Astrotomic\FogTradeSdk\Requests;

use Astrotomic\FogTradeSdk\Collections\AppealCollection;
use Astrotomic\FogTradeSdk\Enums\AppealState;
use Sammyjo20\Saloon\Http\SaloonRequest;
use Sammyjo20\Saloon\Http\SaloonResponse;
use Sammyjo20\Saloon\Traits\Plugins\CastsToDto;

class GetAppealsRequest extends SaloonRequest
{
    use CastsToDto;

    protected ?string $method = 'GET';

    public function __construct(
        public readonly bool $archived,
        public readonly array $selectedStates,
        public readonly int $start = 0,
        public readonly int $length = 20,
    ) {
    }

    public function defineEndpoint(): string
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

    protected function castToDto(SaloonResponse $response): AppealCollection
    {
        return AppealCollection::fromArray($response->json('data'));
    }
}
