<?php

namespace Astrotomic\FogTradeSdk\Collections;

use Astrotomic\FogTradeSdk\Data\Appeal;

final class AppealCollection extends Collection
{
    public static function fromArray(array $data): self
    {
        return static::make($data)->map(
            fn (array $item): Appeal => Appeal::fromArray($item)
        );
    }
}
