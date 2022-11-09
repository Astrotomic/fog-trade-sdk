<?php

namespace Astrotomic\FogTradeSdk\Enums;

enum ReportState: int
{
    case Pending = 1;
    case NeedMoreInfo = 2;
    case ReadyForReview = 3;
    case UnderInvestigation = 4;
    case PendingTag = 5;
    case Resolved = 6;
    case Accepted = 7;
    case Rejected = 8;
    case Invalid = 9;
    case Archived = 10;
}
