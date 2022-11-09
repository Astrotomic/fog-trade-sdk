<?php

namespace Astrotomic\FogTradeSdk\Collections;

use Astrotomic\FogTradeSdk\Data\Report;

final class ReportCollection extends Collection
{
    public static function fromArray(array $data): self
    {
        return static::make($data)->map(
            fn (array $item): Report => Report::fromArray($item)
        );
    }
}
