<?php

namespace Astrotomic\FogTradeSdk\Responses;

use Astrotomic\FogTradeSdk\Exceptions\BadResponseException;
use Astrotomic\FogTradeSdk\Exceptions\ClientException;
use Astrotomic\FogTradeSdk\Exceptions\ServerException;
use Sammyjo20\Saloon\Http\SaloonResponse;

class FogTradeResponse extends SaloonResponse
{
    public function toException(): ?BadResponseException
    {
        return match (true) {
            $this->clientError() => ClientException::fromResponse($this),
            $this->serverError() => ServerException::fromResponse($this),
            $this->failed() => BadResponseException::fromResponse($this),
            default => null,
        };
    }
}
