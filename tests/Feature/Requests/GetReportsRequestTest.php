<?php

use Astrotomic\FogTradeSdk\Data\Report;
use Astrotomic\FogTradeSdk\Enums\ReportState;
use Astrotomic\FogTradeSdk\FogTradeConnector;
use Astrotomic\PhpunitAssertions\NullableTypeAssertions;
use Astrotomic\PhpunitAssertions\UrlAssertions;
use PHPUnit\Framework\Assert;

it('returns archived reports', function (ReportState $state, int $length): void {
    $reports = app(FogTradeConnector::class)->getReports(
        archived: true,
        selectedStates: [
            $state,
        ],
        length: $length,
    );

    Assert::assertContainsOnlyInstancesOf(Report::class, $reports);
    Assert::assertLessThanOrEqual($length, $reports->count());

    $reports->each(function (Report $report) use ($state): void {
        Assert::assertSame($state, $report->state());
        NullableTypeAssertions::assertIsNullableString($report->victim()?->ConvertToUInt64());
        NullableTypeAssertions::assertIsNullableString($report->accused()?->ConvertToUInt64());

        foreach ($report->evidences() as $evidence) {
            UrlAssertions::assertValidLoose($evidence);
        }
    });
})
    ->with(ReportState::cases())
    ->with([10, 20, 50, 100]);

it('can load all reports', function (ReportState $state): void {
    $reports = collect();

    do {
        $response = app(FogTradeConnector::class)->getReports(
            archived: true,
            selectedStates: [
                $state,
            ],
            start: $reports->count(),
            length: 100,
        );

        $reports = $reports->concat($response->toCollection());
    } while ($response->toCollection()->isNotEmpty());

    Assert::assertContainsOnlyInstancesOf(Report::class, $reports);

    $reports->each(function (Report $report) use ($state): void {
        Assert::assertSame($state, $report->state());
        NullableTypeAssertions::assertIsNullableString($report->victim()?->ConvertToUInt64());
        NullableTypeAssertions::assertIsNullableString($report->accused()?->ConvertToUInt64());

        foreach ($report->evidences() as $evidence) {
            UrlAssertions::assertValidLoose($evidence);
        }
    });
})
    ->with(ReportState::cases());
