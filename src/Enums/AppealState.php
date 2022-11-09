<?php

namespace Astrotomic\FogTradeSdk\Enums;

enum AppealState: int
{
    case Pending = 1;
    case NeedMoreInfo = 2;
    case UnderInvestigation = 3;
    case Accepted = 4;
    case Rejected = 5;
    case Invalid = 6;
    case Archived = 7;
}
