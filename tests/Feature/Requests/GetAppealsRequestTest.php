<?php

use Astrotomic\FogTradeSdk\Data\Appeal;
use Astrotomic\FogTradeSdk\Enums\AppealState;
use Astrotomic\FogTradeSdk\FogTradeConnector;
use Astrotomic\PhpunitAssertions\NullableTypeAssertions;
use Astrotomic\PhpunitAssertions\UrlAssertions;
use PHPUnit\Framework\Assert;

it('returns archived appeals', function (AppealState $state): void {
    $appeals = app(FogTradeConnector::class)->appeals(
        selectedStates: [$state],
    );

    Assert::assertContainsOnlyInstancesOf(Appeal::class, $appeals);

    $appeals->each(function (Appeal $appeal) use ($state): void {
        Assert::assertSame($state, $appeal->state());
        NullableTypeAssertions::assertIsNullableString($appeal->appellant()?->ConvertToUInt64());
        Assert::assertContainsOnlyInstancesOf(SteamID::class, $appeal->alts());

        foreach ($appeal->evidences() as $evidence) {
            UrlAssertions::assertValidLoose($evidence);
        }
    });
})->with(AppealState::cases());
