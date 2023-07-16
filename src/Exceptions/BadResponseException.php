<?php

namespace Astrotomic\FogTradeSdk\Exceptions;

use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;
use Throwable;

class BadResponseException extends RequestException
{
    final public function __construct(Response $response, string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($response, $message, $code, $previous);
    }

    public static function fromResponse(Response $response): static
    {
        $body = $response->toPsrResponse()->getBody()->getContents();

        return new static($response, $body, $response->status(), $response->getGuzzleException());
    }
}
