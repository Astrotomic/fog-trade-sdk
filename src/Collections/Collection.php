<?php

namespace Astrotomic\FogTradeSdk\Collections;

abstract class Collection extends \Illuminate\Support\Collection
{
    abstract public static function fromArray(array $data): self;
}
