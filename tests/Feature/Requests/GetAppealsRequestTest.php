<?php

use Astrotomic\FogTradeSdk\Data\Appeal;
use Astrotomic\FogTradeSdk\Enums\AppealState;
use Astrotomic\FogTradeSdk\FogTradeConnector;
use Astrotomic\PhpunitAssertions\NullableTypeAssertions;
use Astrotomic\PhpunitAssertions\UrlAssertions;
use PHPUnit\Framework\Assert;

it('returns archived appeals', function (AppealState $state, int $length): void {
    $appeals = app(FogTradeConnector::class)->getAppeals(
        archived: true,
        selectedStates: [
            $state,
        ],
        length: $length,
    );

    Assert::assertContainsOnlyInstancesOf(Appeal::class, $appeals);
    Assert::assertLessThanOrEqual($length, $appeals->count());

    $appeals->each(function (Appeal $appeal) use ($state): void {
        Assert::assertSame($state, $appeal->state());
        NullableTypeAssertions::assertIsNullableString($appeal->appellant()?->ConvertToUInt64());
        Assert::assertContainsOnlyInstancesOf(SteamID::class, $appeal->alts());

        foreach ($appeal->evidences() as $evidence) {
            UrlAssertions::assertValidLoose($evidence);
        }
    });
})
    ->with(AppealState::cases())
    ->with([10, 20, 50, 100]);
